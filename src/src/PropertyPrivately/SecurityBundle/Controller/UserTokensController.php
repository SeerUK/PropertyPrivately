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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use SeerUK\RestBundle\Annotation\JsonRequest;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Entity\Patcher\Exception\InvalidOperationException;
use SeerUK\RestBundle\Entity\Patcher\Exception\UnsupportedOperationException;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * User Tokens Controller
 */
class UserTokensController extends RestController
{
    /**
     * Requires user to be granted: IS_AUTHENTICATED_FULLY
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get all of the current users' tokens.",
     *  statusCodes={
     *      200="Returned when successful.",
     *      403="Returned when the user is not authorized.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
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

    /**
     * Requires user to be granted: IS_AUTHENTICATED_FULLY
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a given token of the current user.",
     *  statusCodes={
     *      200="Returned when successful.",
     *      403="Returned when the user is not authorized.",
     *      404="Returned when the token is not found.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
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

    /**
     * Requires user to be granted: IS_AUTHENTICATED_ANONYMOUSLY.
     *
     * @JsonRequest(
     *  content={"username": "User123", "password": "Password123"}
     * )
     * @ApiDoc(
     *  description="Create a new token for the current application, with given credentials.",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="A user's username."},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="A user's password."}
     *  },
     *  statusCodes={
     *      201="Returned when successful.",
     *      400="Returned when the request is missing credentials.",
     *      401="Returned when the request has bad credentials.",
     *      403="Returned when the user is not authorized.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
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

    /**
     * Requires user to be granted: IS_AUTHENTICATED_FULLY.<br />
     * Uses JSON PATCH (http://tools.ietf.org/html/rfc6902).<br />
     *
     * @JsonRequest(
     *  content={
     *      {"op": "replace", "path": "description", "value": "Example description."},
     *      {"op": "remove", "path": "description"},
     *      {"op": "add", "path": "description", "value": "Set the description again."},
     *  }
     * )
     * @ApiDoc(
     *  description="Update a given token of the current user.",
     *  parameters={
     *      {"name"="op", "dataType"="string", "required"=true, "description"="A JSON PATCH operation."},
     *      {"name"="path", "dataType"="string", "required"=true, "description"="Specifies the location of where the operation is performed."},
     *      {"name"="from", "dataType"="string", "required"=false, "description"="Specifies the location of where the value of a 'copy' or 'move' operation should originate."},
     *      {"name"="value", "dataType"="string", "required"=false, "description"="Specifies a value for an add, replace or test operation."}
     *  },
     *  statusCodes={
     *      200="Returned when successful.",
     *      400={
     *          "Returned when the request is malformed.",
     *          "Returned when the request is missing operations.",
     *          "Returned when invalid operations are given in the request.",
     *          "Returned when the entity doesn't support an operation."
     *      },
     *      403="Returned when the user is not authorized.",
     *      404="Returned when the token is not found.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
    public function patchAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $operations = json_decode($this->get('request')->getContent());
        $user       = $this->get('security.context')->getToken()->getUser();
        $validator  = $this->get('validator');
        $tokenRepo  = $this->get('pp_security.token_repository');
        $token      = $tokenRepo->findOneBy(array(
            'id'   => $id,
            'user' => $user->getId()
        ));

        if ( ! $token) {
            throw new NotFoundHttpException('Token not found.');
        }

        if ( ! $operations) {
            throw new BadRequestHttpException('Missing PATCH operations.');
        }

        try {
            $patcher = $this->get('pp_security.token_patcher');
            $token   = $patcher->patch($token, $operations);
        } catch (InvalidOperationException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        } catch (UnsupportedOperationException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        $errors = $validator->validate($token);

        if (count($errors) > 0) {
            throw new ConstraintViolationException($errors);
        }

        $tokenRepo->update($token);

        return $this->getPatchResponse('pp_security_user_tokens_get', array(
            'id' => $token->getId()
        ));

    }


    /**
     * Requires user to be granted: IS_AUTHENTICATED_FULLY
     *
     * @ApiDoc(
     *  description="Deletes all tokens of the current user.",
     *  statusCodes={
     *      204="Returned when successful.",
     *      403="Returned when the user is not authorized.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
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



    /**
     * Requires user to be granted: IS_AUTHENTICATED_FULLY
     *
     * @ApiDoc(
     *  description="Deletes a given token of the current user.",
     *  statusCodes={
     *      204="Returned when successful.",
     *      403="Returned when the user is not authorized.",
     *      404="Returned when the token is not found.",
     *      500="Returned when there is an internal error."
     *  }
     * )
     */
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
