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
 * Get Resource Assembler
 */
class GetResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssemlber::assemble()
     */
    public function assemble(array $nested = array())
    {
        $this->setVariable('links', $this->rootResource->getLinks());

        $saleAssembler = new OfferResourceAssembler($this->router);
        $saleAssembler->setVariable('offer', $this->getVariable('offer'));
        $saleAssembler->setRootResource($this->getRootResource());

        $resource = $saleAssembler->assemble();
        $resource->removeLink('self');
        $resource->addLinks($this->assembleLinks());

        return $resource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    private function assembleLinks()
    {
        $offer    = $this->getVariable('offer');
        $sale     = $offer->getSale();
        $property = $sale->getProperty();

        $links = $this->getVariable('links');
        $links['offer:property'] = new Link($this->router->generate('pp_property_properties_get', ['id' => $property->getId()]));
        $links['offer:sale']     = new Link($this->router->generate('pp_property_sales_get', ['id' => $sale->getId()]));

        return $links;
    }
}
