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
use PropertyPrivately\PropertyBundle\Entity\Image;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\PropertyBundle\Form\Type\ImageType;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

/**
 * User Properties Images Controller
 */
class UserPropertiesImagesController extends RestController
{
    public function getAllAction($propId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user     = $this->get('security.context')->getToken()->getUser();
        $imgRepo  = $this->get('pp_property.image_repository');
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $propId,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.user_properties_images.get_all_assembler');
        $assembler->setVariable('images', $imgRepo->findBy([
            'property' => $property->getId()
        ]));

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($propId, $imageId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user     = $this->get('security.context')->getToken()->getUser();
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $propId,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $imgRepo = $this->get('pp_property.image_repository');
        $image   = $imgRepo->findOneBy([
            'id'       => $imageId,
            'property' => $property->getId()
        ]);

        if ( ! $image) {
            throw new NotFoundHttpException(ErrorMessages::IMAGE_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.user_properties_images.get_assembler');
        $assembler->setVariable('image', $image);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction($propId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request  = $this->get('request');
        $user     = $this->get('security.context')->getToken()->getUser();
        $imgRepo  = $this->get('pp_property.image_repository');
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $propId,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $input = ['file' => $request->files->get('file')];

        $form = $this->createForm(new ImageType(), new Image());
        $form->submit($input);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $image = $form->getData();
        $image->setProperty($property);
        $imgRepo->persist($image);

        return $this->getPostResponse('pp_property_user_properties_images_get', array(
            'propId'  => $property->getId(),
            'imageId' => $image->getId()
        ));
    }

    public function patchAction($propId, $imageId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $request  = $this->get('request');
        $user     = $this->get('security.context')->getToken()->getUser();
        $imgRepo  = $this->get('pp_property.image_repository');
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $propId,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $image = $imgRepo->findOneBy([
            'id'       => $imageId,
            'property' => $property->getId()
        ]);

        if ( ! $image) {
            throw new NotFoundHttpException(ErrorMessages::IMAGE_NOT_FOUND);
        }

        $form = $this->createForm(new ImageType(true), $image);
        $form->submit(json_decode($request->getContent(), true), false);

        if ( ! $form->isValid()) {
            throw new ConstraintViolationException(
                $this->getFormConstraintViolationList($form)
            );
        }

        $image = $form->getData();
        $imgRepo->update($image);

        return $this->getPatchResponse('pp_property_user_properties_images_get', array(
            'propId'  => $property->getId(),
            'imageId' => $image->getId()
        ));
    }

    public function deleteAction($propId, $imageId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user     = $this->get('security.context')->getToken()->getUser();
        $imgRepo  = $this->get('pp_property.image_repository');
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $propId,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $image = $imgRepo->findOneBy([
            'id'       => $imageId,
            'property' => $property->getId()
        ]);

        if ( ! $image) {
            throw new NotFoundHttpException(ErrorMessages::IMAGE_NOT_FOUND);
        }

        $imgRepo->remove($image);

        return new Response(null, 204);
    }
}
