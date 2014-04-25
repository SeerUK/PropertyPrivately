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
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy(['id' => $id]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $saleRepo = $this->get('pp_property.sale_repository');
        $sales    = $saleRepo->findActiveByPropertyId($property->getId());

        if ( ! count($sales)) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.properties.get_assembler');
        $assembler->setVariable('property', $property);

        return new JsonResponse($assembler->assemble());
    }
}
