<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Entity\Patcher\Exception\InvalidOperationException;
use SeerUK\RestBundle\Entity\Patcher\Exception\UnsupportedOperationException;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\SecurityBundle\Entity\Person;
use PropertyPrivately\SecurityBundle\Form\Type\PersonType;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * User Controller
 */
class UserController extends RestController
{
    public function getAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $assembler = $this->get('pp_security.resource_assembler.user.get_assembler');
        $assembler->setVariable('user', $user);

        return new JsonResponse($assembler->assemble());
    }

    public function patchAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request  = $this->get('request');
        $userRepo = $this->get('pp_security.user_repository');
        $user     = $this->get('security.context')->getToken()->getUser();

        $person = $user->getPerson();
        if ( ! $person) {
            $person = new Person();
            $person->setUser($user);
        }

        $form = $this->createForm(new PersonType(), $person);
        $form->submit(json_decode($request->getContent(), true), false);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $person = $form->getData();
        $user->setPerson($person);
        $userRepo->update($user);

        return $this->getPatchResponse('pp_security_user_get');
    }
}
