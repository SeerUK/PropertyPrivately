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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\HttpFoundation\RedirectResponse;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;

/**
 * Token Controller
 */
class TokenController extends RestController
{
    public function getAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $tokenRepo = $this->get('pp_security.token_repository');
        $assembler = $this->get('pp_security.resource_assembler.token.get_assembler');
        $assembler->setVariable('token', $tokenRepo->findOneBy(array(
            'id'   => $id,
            'user' => $user->getId()
        )));

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            throw new AccessDeniedException();
        }

        $request      = $this->get('request');
        $resource     = $this->get('seer_uk_rest.hal_root_resource');
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

        return $this->createInternalRequest('pp_security_token_get', array(
            'id' => $token->getId()
        ), JsonResponse::HTTP_CREATED, array(
            'X-API-Key' => $token->getToken()
        ));
    }
}
