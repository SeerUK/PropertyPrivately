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
use PropertyPrivately\PropertyBundle\Entity\Address;
use PropertyPrivately\PropertyBundle\Entity\Property;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\PropertyBundle\Input\Dictionary\PropertyInputDictionary;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

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

        if ( ! $this->getUser() || $property->getUser()->getId() !== $this->getUser()->getId()) {
            $saleRepo = $this->get('pp_property.sale_repository');
            $sales    = $saleRepo->findActiveByPropertyId($property->getId());

            if ( ! count($sales)) {
                throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
            }
        }

        $assembler = $this->get('pp_property.resource_assembler.properties.get_assembler');
        $assembler->setVariable('property', $property);

        return new JsonResponse($assembler->assemble());
    }

    public function postAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user   = $this->get('security.context')->getToken()->getUser();
        $filter = $this->createInputFilter(new PropertyInputDictionary(), [new Address(), new Property()]);
        $filter->handleRequest($this->get('request'));

        if ( ! $filter->isValid()) {
            throw new ConstraintViolationException($filter->getErrors());
        }

        $property = $filter->getData(Property::class);
        $property->setUser($user);
        $this->get('pp_property.property_repository')->persist($property);

        $address = $filter->getData(Address::class);
        $address->setProperty($property);
        $this->get('pp_property.address_repository')->persist($address);

        return $this->getPostResponse('pp_property_properties_get', array(
            'id' => $property->getId()
        ));
    }

    public function patchAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user     = $this->get('security.context')->getToken()->getUser();
        $propRepo = $this->get('pp_property.property_repository');
        $property = $propRepo->findOneBy([
            'id'   => $id,
            'user' => $user->getId()
        ]);

        if ( ! $property) {
            throw new NotFoundHttpException(ErrorMessages::PROPERTY_NOT_FOUND);
        }

        $address = $property->getAddress();
        if ( ! $address) {
            $address = new Address();
        }

        $filter = $this->createInputFilter(new PropertyInputDictionary(true), [$address, $property]);
        $filter->handleRequest($this->get('request'));

        if ( ! $filter->isValid()) {
            throw new ConstraintViolationException($filter->getErrors());
        }

        $property = $filter->getData(Property::class);
        $this->get('pp_property.property_repository')->update($property);

        $address = $filter->getData(Address::class);
        $this->get('pp_property.address_repository')->update($address);

        return $this->getPatchResponse('pp_property_properties_get', array(
            'id' => $property->getId()
        ));
    }

    public function deleteAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
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
