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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Entity\User;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\SecurityBundle\Form\Type\UserType;
use PropertyPrivately\CoreBundle\Form\FormErrorOriginHandler;

/**
 * Users Controller
 */
class UsersController extends RestController
{
    public function directoryAction()
    {
        $assembler = $this->get('pp_security.resource_assembler.users.directory_assembler');

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($username)
    {
        $userRepo = $this->get('pp_security.user_repository');
        $user     = $userRepo->findOneBy(array(
            'username' => $username
        ));

        if ( ! $user) {
            throw new NotFoundHttpException('User not found.');
        }

        $assembler = $this->get('pp_security.resource_assembler.users.get_assembler');
        $assembler->setVariable('user', $user);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_ANONYMOUSLY);
        }

        $request   = $this->get('request');
        $roleRepo  = $this->get('pp_security.role_repository');
        $userRepo  = $this->get('pp_security.user_repository');

        $form = $this->createForm(new UserType(), new User());
        $form->submit(json_decode($request->getContent(), true));

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $user = $form->getData();
        $user->addRole($roleRepo->findOneBy(['role' => 'ROLE_USER']));
        $userRepo->persist($user);

        return $this->getPostResponse('pp_security_users_get', array(
            'username' => $user->getUsername()
        ));
    }
}
