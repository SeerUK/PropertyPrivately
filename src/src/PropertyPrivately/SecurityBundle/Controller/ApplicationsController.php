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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * Applications Controller
 */
class ApplicationsController extends RestController
{
    public function directoryAction()
    {
        $assembler = $this->get('pp_security.resource_assembler.applications.directory_assembler');

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($id)
    {
        $appRepo = $this->get('pp_security.application_repository');
        $app     = $appRepo->findOneBy(array(
            'id' => $id
        ));

        if ( ! $app) {
            throw new NotFoundHttpException(ErrorMessages::APPLICATION_NOT_FOUND);
        }

        $assembler = $this->get('pp_security.resource_assembler.applications.get_assembler');
        $assembler->setVariable('application', $app);

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $assembler->setVariable('user', $this->get('security.context')->getToken()->getUser());
        }

        return new JsonResponse($assembler->assemble());
    }
}
