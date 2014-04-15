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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

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

        $user      = $this->get('security.context')->getToken()->getUser();
        $tokenRepo = $this->get('pp_security.token_repository');
        $assembler = $this->get('pp_security.resource_assembler.user_tokens.get_all_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('tokens', $tokenRepo->findBy(array(
            'user' => $user->getId()
        )));

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
            throw new NotFoundHttpException('Token not found.');
        }

        $assembler = $this->get('pp_security.resource_assembler.user_tokens.get_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('token', $token);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_ANONYMOUSLY);
        }

        $request      = $this->get('request');
        $tokenBuilder = $this->get('pp_security.token_builder');
        $tokenRepo    = $this->get('pp_security.token_repository');

        try {
            $token = $tokenBuilder->build(
                $request->headers->get('X-API-App-Secret'),
                json_decode($request->getContent())
            );
        } catch (MissingMandatoryParametersException $e) {
            throw new BadRequestHttpException('Missing app secret, or user credentials.', $e);
        } catch (BadCredentialsException $e) {
            throw new UnauthorizedHttpException(null, 'Bad credentials.', $e);
        }

        $tokenRepo->persist($token);

        return $this->createInternalRequest('pp_security_user_tokens_get', array(
            'id' => $token->getId()
        ), JsonResponse::HTTP_CREATED, array(
            'X-API-Key' => $token->getToken()
        ));
    }
}
