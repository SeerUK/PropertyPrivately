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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Entity\Patcher\Exception\InvalidOperationException;
use SeerUK\RestBundle\Entity\Patcher\Exception\UnsupportedOperationException;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\SecurityBundle\Form\Type\TokenType;

/**
 * User Tokens Controller
 */
class UserTokensController extends RestController
{
    public function getAllAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request   = $this->get('request');
        $user      = $this->get('security.context')->getToken()->getUser();
        $tokenRepo = $this->get('pp_security.token_repository');

        $findBy = array();
        $findBy['user'] = $user->getId();

        if ($request->query->has('application')) {
            $findBy['application'] = $request->query->get('application');
        }

        $assembler = $this->get('pp_security.resource_assembler.user_tokens.get_all_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('tokens', $tokenRepo->findBy($findBy));

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $tokenRepo = $this->get('pp_security.token_repository');
        $token     = $tokenRepo->findOneBy(array(
            'id'   => $id,
            'user' => $user->getId()
        ));

        if ( ! $token) {
            throw new NotFoundHttpException(ErrorMessages::TOKEN_NOT_FOUND);
        }

        $assembler = $this->get('pp_security.resource_assembler.user_tokens.get_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('token', $token);

        return new JsonResponse($assembler->assemble());
    }

    public function deleteAllAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user         = $this->get('security.context')->getToken()->getUser();
        $tokenGateway = $this->get('pp_security.token_gateway');
        $tokenGateway->removeAllByUserId($user->getId());

        return new Response(null, 204);
    }

    public function deleteAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $tokenRepo = $this->get('pp_security.token_repository');
        $token     = $tokenRepo->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        if ( ! $token) {
            throw new NotFoundHttpException(ErrorMessages::TOKEN_NOT_FOUND);
        }

        $tokenRepo->remove($token);

        return new Response(null, 204);
    }
}
