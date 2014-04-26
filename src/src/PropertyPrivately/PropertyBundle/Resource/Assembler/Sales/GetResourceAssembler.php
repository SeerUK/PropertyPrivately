<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\Sales;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\SaleResourceAssembler;

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

        $saleAssembler = new SaleResourceAssembler($this->router);
        $saleAssembler->setVariable('sale', $this->getVariable('sale'));
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
        $sale     = $this->getVariable('sale');
        $property = $sale->getProperty();
        $links    = $this->getVariable('links');
        $links['sale:property'] = new Link($this->router->generate('pp_property_properties_get', ['id' => $property->getId()]));

        return $links;
    }
}
