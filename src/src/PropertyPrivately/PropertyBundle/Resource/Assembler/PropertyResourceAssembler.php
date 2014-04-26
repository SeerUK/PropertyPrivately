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
use PropertyPrivately\PropertyBundle\Entity\Address;

/**
 * Property Resource Assembler
 */
class PropertyResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssemlber::assemble()
     */
    public function assemble(array $nested = array())
    {
        $property = $this->getVariable('property');

        $this->rootResource->setVariables($property->toArray());
        $this->rootResource->addLinks($this->assembleLinks());

        if (in_array('address', $nested)) {
            $address = $property->getAddress();
            if ( ! $address) {
                $address = new Address();
            }

            $address = $address->toArray();
            unset($address['id']);

            foreach ($address as $key => $value) {
                $this->rootResource->setVariable($key, $value);
            }
        }

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    private function assembleLinks()
    {
        $property = $this->getVariable('property');

        $links = array();
        $links['self'] = new Link($this->router->generate('pp_property_properties_get', ['id' => $property->getId()]));

        return $links;
    }
}
