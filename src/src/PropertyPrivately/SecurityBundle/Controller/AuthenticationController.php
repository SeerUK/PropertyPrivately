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
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Entity\Token;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\SecurityBundle\Form\Type\TokenType;

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

        $request       = $this->get('request');
        $appRepo       = $this->get('pp_security.application_repository');
        $tokenRepo     = $this->get('pp_security.token_repository');
        $generator     = $this->get('pp_security.token_generator');
        $userRepo      = $this->get('pp_security.user_repository');
        $userValidator = $this->get('pp_security.user_validator');

        $credentials = json_decode($request->getContent());

        if ( ! $request->headers->has('X-API-App-Token')) {
            throw new BadRequestHttpException('Missing app token.');
        }

        if ( ! is_object($credentials) || empty($credentials->username)
            || empty($credentials->password)) {
            throw new BadRequestHttpException('Missing user credentials.');
        }

        $application = $appRepo->findOneByToken($request->headers->get('X-API-App-Token'));
        $user        = $userRepo->findOneByUsername($credentials->username);

        if ( ! $application) {
            throw new UnauthorizedHttpException(null, 'Invalid application token.');
        }

        if ( ! $user
            || ! $userValidator->validate($user, $credentials->username, $credentials->password)) {
            throw new UnauthorizedHttpException(null, 'Invalid user credentials.');
        }

        $form = $this->createForm(new TokenType(), new Token());
        $form->submit([
            'application' => $application->getId(),
            'user'        => $user->getId(),
            'token'       => $generator->generate()
        ]);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $token = $form->getData();
        $tokenRepo->persist($token);

        return $this->getPostResponse('pp_security_user_tokens_get', array(
            'id' => $token->getId()
        ), array(
            'X-API-App-Token'  => $request->headers->get('X-API-App-Token'),
            'X-API-User-Token' => $token->getToken()
        ));
    }
}
