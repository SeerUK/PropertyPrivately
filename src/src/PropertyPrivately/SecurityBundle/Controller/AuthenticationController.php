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

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * Authentication Controller
 */
class AuthenticationController extends RestController
{
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
                $request->headers->get('X-API-App-Token'),
                json_decode($request->getContent())
            );
        } catch (MissingMandatoryParametersException $e) {
            throw new BadRequestHttpException('Missing app secret, or user credentials.', $e);
        } catch (BadCredentialsException $e) {
            throw new UnauthorizedHttpException(null, 'Bad credentials.', $e);
        }

        $tokenRepo->persist($token);

        return $this->getPostResponse('pp_security_user_tokens_get', array(
            'id' => $token->getId()
        ), array(
            'X-API-App-Token'  => $request->headers->get('X-API-App-Token'),
            'X-API-User-Token' => $token->getToken()
        ));
    }
}
