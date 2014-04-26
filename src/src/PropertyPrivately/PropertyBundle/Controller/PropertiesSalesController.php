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
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use PropertyPrivately\PropertyBundle\Entity\Sale;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\PropertyBundle\Input\Dictionary\SaleInputDictionary;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

/**
 * Properties Sales Controller
 */
class PropertiesSalesController extends RestController
{
    public function getAllAction($propId)
    {
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy(['id' => $propId]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $saleRepo = $this->get('pp_property.sale_repository');

        if ( ! $this->getUser() || $property->getUser()->getId() !== $this->getUser()->getId()) {
            $sales = $saleRepo->findActiveByPropertyId($property->getId());

            if ( ! count($sales)) {
                throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
            }
        } else {
            $sales = $saleRepo->findBy(['property' => $property->getId()]);
        }

        $assembler = $this->get('pp_property.resource_assembler.properties_sales.get_all_assembler');
        $assembler->setVariable('sales', $sales);

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($propId, $saleId)
    {
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy(['id' => $propId]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $saleRepo = $this->get('pp_property.sale_repository');

        if ( ! $this->getUser() || $property->getUser()->getId() !== $this->getUser()->getId()) {
            $sale = $saleRepo->findOneActiveBySaleIdAndPropertyId($saleId, $property->getId());

            if ( ! $sale) {
                throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
            }
        } else {
            $sale = $saleRepo->findOneBy([
                'id'       => $saleId,
                'property' => $property->getId()
            ]);
        }

        $assembler = $this->get('pp_property.resource_assembler.properties_sales.get_assembler');
        $assembler->setVariable('sale', $sale);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction($propId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
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

        $saleRepo = $this->get('pp_property.sale_repository');
        $sales    = $saleRepo->findPotentiallyActiveByPropertyId($property->getId());

        if (count($sales) > 0) {
            throw new ConflictHttpException(ErrorMessages::SALE_CREATE_CONFLICT);
        }

        $filter = $this->createInputFilter(new SaleInputDictionary(), [new Sale()]);
        $filter->handleRequest($this->get('request'));

        if ( ! $filter->isValid()) {
            throw new ConstraintViolationException($filter->getErrors());
        }

        $sale = $filter->getData(Sale::class);
        $sale->setProperty($property);
        $saleRepo->persist($sale);

        return $this->getPostResponse('pp_property_properties_sales_get', array(
            'propId' => $property->getId(),
            'saleId' => $sale->getId()
        ));
    }

    public function patchAction($propId, $saleId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
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

        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneBy([
            'id'       => $saleId,
            'property' => $propId
        ]);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $filter = $this->createInputFilter(new SaleInputDictionary(true), [$sale]);
        $filter->handleRequest($this->get('request'), false);

        if ( ! $filter->isValid()) {
            throw new ConstraintViolationException($filter->getErrors());
        }

        $sale = $filter->getData(Sale::class);
        $saleRepo->update($sale);

        return $this->getPatchResponse('pp_property_properties_sales_get', array(
            'propId' => $property->getId(),
            'saleId' => $sale->getId()
        ));
    }

    public function deleteAction($propId, $saleId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
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

        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneBy([
            'id'       => $saleId,
            'property' => $propId
        ]);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $saleRepo->remove($sale);

        return new Response(null, 204);
    }
}
