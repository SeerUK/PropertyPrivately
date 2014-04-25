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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;

/**
 * Properties Images Controller
 */
class PropertiesImagesController extends RestController
{
    public function getAllAction($propId)
    {
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy(['id' => $propId]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $saleRepo = $this->get('pp_property.sale_repository');
        $sales    = $saleRepo->findActiveByPropertyId($property->getId());

        if ( ! count($sales)) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $imgRepo   = $this->get('pp_property.image_repository');
        $assembler = $this->get('pp_property.resource_assembler.properties_images.get_all_assembler');
        $assembler->setVariable('images', $imgRepo->findBy([
            'property' => $property->getId()
        ]));

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($propId, $imageId)
    {
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy(['id' => $propId]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $saleRepo = $this->get('pp_property.sale_repository');
        $sales    = $saleRepo->findActiveByPropertyId($property->getId());

        if ( ! count($sales)) {
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

        $assembler = $this->get('pp_property.resource_assembler.properties_images.get_assembler');
        $assembler->setVariable('image', $image);

        return new JsonResponse($assembler->assemble());
    }
}
