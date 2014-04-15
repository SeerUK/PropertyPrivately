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
class UserApplicationsController extends RestController
{
    public function getAllAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $appRepo   = $this->get('pp_security.application_repository');
        $assembler = $this->get('pp_security.resource_assembler.user_applications.get_all_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('applications', $appRepo->findBy(array(
            'user' => $user->getId()
        )));

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user        = $this->get('security.context')->getToken()->getUser();
        $appRepo     = $this->get('pp_security.application_repository');
        $application = $appRepo->findOneBy(array(
            'id'   => $id,
            'user' => $user->getId()
        ));

        if ( ! $application) {
            throw new NotFoundHttpException('Application not found.');
        }

        $assembler = $this->get('pp_security.resource_assembler.user_applications.get_assembler');
        $assembler->setVariable('user', $user);
        $assembler->setVariable('application', $application);

        return new JsonResponse($assembler->assemble());
    }
}
