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
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
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

        return $this->getPostResponse('pp_security_user_tokens_get', array(
            'id' => $token->getId()
        ), array(
            'X-API-App-Secret' => $request->headers->get('X-API-App-Secret'),
            'X-API-Key'        => $token->getToken()
        ));
    }

    public function patchAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user       = $this->get('security.context')->getToken()->getUser();
        $operations = json_decode($this->get('request')->getContent());
        $tokenRepo  = $this->get('pp_security.token_repository');
        $token      = $tokenRepo->findOneBy(array(
            'id'   => $id,
            'user' => $user->getId()
        ));

        if ( ! $token) {
            throw new NotFoundHttpException('Token not found.');
        }

        $token->setDescription('ssfdisduf sdifhsh fsdf hsdif sdfh sdu siuf suif usi fiusdf hsudf hsdf hisdhusdhf usd fuihs fuhs fhsdfsdui fhs usid uisdf sdh uhsd fhis usih');
        $token->setEnabled('thisisnotaboolean');

        // try {
        //     $patcher = $this->get('pp_security.token_patcher');
        //     $token   = $patcher->patch($token, $operations);
        // } catch (\Exception $e) {
        //     // Do something
        // }
        // $operations = json_decode($this->get('request')->getContent());

        $validator  = $this->get('validator');
        $errors     = $validator->validate($token);

        if (count($errors) > 0) {
            throw new ConstraintViolationException($errors);
        }

        // var_dump($validator);
        var_dump($operations);
        var_dump($errors);
        var_dump(count($errors));
        exit;


        // Some condition of validity:
            return $this->getPatchResponse('pp_security_user_tokens_get', array(
                'id' => $token->getId()
            ));
        // End

        // Throw 422?
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
            throw new NotFoundHttpException('Token not found.');
        }

        $tokenRepo->remove($token);

        return new Response(null, 204);
    }
}
