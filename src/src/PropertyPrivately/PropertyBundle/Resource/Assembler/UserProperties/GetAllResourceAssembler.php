<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\UserProperties;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\PropertyResourceAssembler;

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
        $properties = $this->getVariable('properties');
        $this->rootResource->setVariable('total', count($properties));
        $this->rootResource->addLinks($this->assembleLinks());

        $propertyAssembler = new PropertyResourceAssembler($this->router);

        foreach ($properties as $property) {
            $propertyAssembler->setRootResource(new Resource());
            $propertyAssembler->setVariable('property', $property);

            $this->rootResource->addResource('properties', $propertyAssembler->assemble(), true);
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
        $links = array();

        $propertyLink = new Link($this->generateRouteTemplate('pp_property_properties_get'));
        $propertyLink->setTemplated(true);
        $propertyLink->setName('Specific Property');

        $links['properties:property'] = $propertyLink;

        return $links;
    }
}
