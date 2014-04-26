<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;

/**
 * Offer Resource Assembler
 */
class OfferResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssemlber::assemble()
     */
    public function assemble(array $nested = array())
    {
        $offer = $this->getVariable('offer');

        $this->rootResource->setVariables($offer->toArray());
        $this->rootResource->addLinks($this->assembleLinks());

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    private function assembleLinks()
    {
        $offer = $this->getVariable('offer');
        $user  = $offer->getUser();
        $sale  = $offer->getSale();

        $links = array();
        $links['self']       = new Link($this->router->generate('pp_property_sales_offers_get', ['saleId' => $sale->getId(), 'offerId' => $offer->getId()]));
        $links['offer:user'] = new Link($this->router->generate('pp_security_users_get', ['username' => $user->getUsername()]));

        return $links;
    }
}
