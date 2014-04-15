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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * User Applications Controller
 */
class UserTokensApplicationController extends RestController
{
    public function getAllAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user        = $this->get('security.context')->getToken()->getUser();
        $appRepo     = $this->get('pp_security.application_repository');
        $tokenRepo   = $this->get('pp_security.token_repository');
        $application = $appRepo->findOneBy(array(
            'id' => $id
        ));

        if ( ! $application) {
            throw new NotFoundHttpException('Application not found.');
        }

        $assembler = $this->get('pp_security.resource_assembler.user_tokens_application.get_all_assembler');
        $assembler->setVariable('application', $application);
        $assembler->setVariable('tokens', $tokenRepo->findBy(array(
            'application' => $id,
            'user'        => $user->getId()
        )));

        return new JsonResponse($assembler->assemble());
    }
}
