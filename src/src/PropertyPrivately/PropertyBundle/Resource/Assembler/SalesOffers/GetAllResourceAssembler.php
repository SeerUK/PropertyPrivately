<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\SalesOffers;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\OfferResourceAssembler;

/**
 * Get All Action Assembler
 */
class GetAllResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $offers = $this->getVariable('offers');
        $this->rootResource->setVariable('total', count($offers));
        $this->rootResource->addLinks($this->assembleLinks());

        $offerAssembler = new OfferResourceAssembler($this->router);

        foreach ($offers as $offer) {
            $offerAssembler->setRootResource(new Resource());
            $offerAssembler->setVariable('offer', $offer);

            $this->rootResource->addResource('offers', $offerAssembler->assemble(), true);
        }

        return $this->rootResource;
    }

    private function assembleLinks()
    {
        $sale     = $this->getVariable('sale');
        $property = $sale->getProperty();

        $links = array();
        $links['offers:property'] = new Link($this->router->generate('pp_property_properties_get', ['id' => $property->getId()]));
        $links['offers:sale']     = new Link($this->router->generate('pp_property_sales_get', ['id' => $sale->getId()]));

        return $links;
    }
}
