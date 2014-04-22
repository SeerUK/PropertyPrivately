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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\SecurityBundle\Entity\Application;
use PropertyPrivately\SecurityBundle\Form\Type\ApplicationType;

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
        $appRepo     = $this->get('pp_security.application_repository');
        $application = $appRepo->findOneBy(array(
            'id' => $id
        ));

        if ( ! $application) {
            throw new NotFoundHttpException(ErrorMessages::APPLICATION_NOT_FOUND);
        }

        $assembler = $this->get('pp_security.resource_assembler.applications.get_assembler');
        $assembler->setVariable('application', $application);

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $assembler->setVariable('user', $this->get('security.context')->getToken()->getUser());
        }

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request = $this->get('request');
        $user    = $this->get('security.context')->getToken()->getUser();
        $appRepo = $this->get('pp_security.application_repository');

        $form = $this->createForm(new ApplicationType(), new Application());
        $form->submit(json_decode($request->getContent(), true), false);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $application = $form->getData();
        $application->setUser($user);
        $appRepo->persist($application);

        return $this->getPostResponse('pp_security_applications_get', array(
            'id' => $application->getId()
        ));
    }

    public function patchAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request     = $this->get('request');
        $user        = $this->get('security.context')->getToken()->getUser();
        $appRepo     = $this->get('pp_security.application_repository');
        $application = $appRepo->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        if ( ! $application) {
            throw new NotFoundHttpException(ErrorMessages::APPLICATION_NOT_FOUND);
        }

        $form = $this->createForm(new ApplicationType(), $application);
        $form->submit(json_decode($request->getContent(), true), false);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $application = $form->getData();
        $appRepo->update($application);

        return $this->getPatchResponse('pp_security_applications_get', array(
            'id' => $application->getId()
        ));
    }

    public function deleteAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user        = $this->get('security.context')->getToken()->getUser();
        $appRepo     = $this->get('pp_security.application_repository');
        $application = $appRepo->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        if ( ! $application) {
            throw new NotFoundHttpException(ErrorMessages::APPLICATION_NOT_FOUND);
        }

        $appRepo->remove($application);

        return new Response(null, 204);
    }
}
