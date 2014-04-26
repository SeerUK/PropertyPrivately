<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\UsersSales;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\SaleResourceAssembler;

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
        $sales = $this->getVariable('sales');
        $this->rootResource->setVariable('total', count($sales));

        $saleAssembler = new SaleResourceAssembler($this->router);

        foreach ($sales as $sale) {
            $saleAssembler->setRootResource(new Resource());
            $saleAssembler->setVariable('sale', $sale);

            $this->rootResource->addResource('sales', $saleAssembler->assemble(), true);
        }

        return $this->rootResource;
    }
}
