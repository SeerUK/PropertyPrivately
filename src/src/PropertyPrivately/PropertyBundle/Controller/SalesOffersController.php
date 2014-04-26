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
use PropertyPrivately\PropertyBundle\Entity\Offer;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\PropertyBundle\Input\Dictionary\OfferInputDictionary;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

/**
 * Sales Offer Controller
 */
class SalesOffersController extends RestController
{
    public function getAllAction($saleId)
    {
        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneActiveById($saleId);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $offerRepo = $this->get('pp_property.offer_repository');
        $offers    = $offerRepo->findBy([
            'sale' => $sale->getId()
        ]);

        $assembler = $this->get('pp_property.resource_assembler.sales_offers.get_all_assembler');
        $assembler->setVariable('sale', $sale);
        $assembler->setVariable('offers', $offers);

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($saleId, $offerId)
    {
        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneActiveById($saleId);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $offerRepo = $this->get('pp_property.offer_repository');
        $offer     = $offerRepo->findOneBy([
            'id'   => $offerId,
            'sale' => $sale->getId()
        ]);

        if ( ! $offer) {
            throw new NotFoundHttpException(ErrorMessages::OFFER_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.sales_offers.get_assembler');
        $assembler->setVariable('offer', $offer);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction($saleId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneActiveById($saleId);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $user     = $this->get('security.context')->getToken()->getUser();
        $propUser = $sale->getProperty()->getUser();

        if ($user->getId() === $propUser->getId()) {
            throw new ConflictHttpException(ErrorMessages::OFFER_CREATE_CONFLICT);
        }

        $filter = $this->createInputFilter(new OfferInputDictionary(), [new Offer()]);
        $filter->handleRequest($this->get('request'));

        if ( ! $filter->isValid()) {
            throw new ConstraintViolationException($filter->getErrors());
        }

        $offer = $filter->getData(Offer::class);
        $offer->setSale($sale);
        $offer->setUser($user);
        $this->get('pp_property.offer_repository')->persist($offer);

        return $this->getPostResponse('pp_property_sales_offers_get', array(
            'saleId' => $sale->getId(),
            'offerId' => $offer->getId(),
        ));
    }

    public function deleteAction($saleId, $offerId)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneBy(['id' => $saleId]);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $offerRepo = $this->get('pp_property.offer_repository');
        $offer     = $repository->findOneBy([
            'id'   => $offerId,
            'user' => $user->getId()
        ]);

        if ( ! $offer) {
            throw new NotFoundHttpException(ErrorMessages::OFFER_NOT_FOUND);
        }

        $repository->remove($offer);

        return new Response(null, 204);
    }
}
