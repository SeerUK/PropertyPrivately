<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\CoreBundle\Form\FormErrorOriginHandler;
use PropertyPrivately\PropertyBundle\Entity\Property;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\PropertyBundle\Form\Type\PropertyType;

/**
 * Properties Controller
 */
class PropertiesController extends RestController
{
    public function directoryAction()
    {
        $assembler = $this->get('pp_property.resource_assembler.properties.directory_assembler');

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($id)
    {
        $repository = $this->get('pp_property.property_repository');
        $property   = $repository->findOneBy(['id' => $id]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.properties.get_assembler');
        $assembler->setVariable('property', $property);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request    = $this->get('request');
        $user       = $this->get('security.context')->getToken()->getUser();
        $repository = $this->get('pp_property.property_repository');

        $form = $this->createForm(new PropertyType(), new Property());
        $form->submit(json_decode($request->getContent(), true));

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $property = $form->getData();
        $property->setUser($user);
        $repository->persist($property);

        return $this->getPostResponse('pp_property_properties_get', array(
            'id' => $property->getId()
        ));
    }

    public function patchAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request    = $this->get('request');
        $user       = $this->get('security.context')->getToken()->getUser();
        $repository = $this->get('pp_property.property_repository');
        $property   = $repository->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        $form = $this->createForm(new PropertyType(), $property);
        $form->submit(json_decode($request->getContent(), true), false);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $property = $form->getData();
        $repository->update($property);

        return $this->getPatchResponse('pp_property_properties_get', array(
            'id' => $property->getId()
        ));
    }

    public function deleteAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user       = $this->get('security.context')->getToken()->getUser();
        $repository = $this->get('pp_property.property_repository');
        $property   = $repository->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $repository->remove($property);

        return new Response(null, 204);
    }
}
