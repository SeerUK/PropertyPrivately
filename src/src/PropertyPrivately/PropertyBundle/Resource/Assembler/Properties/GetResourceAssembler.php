<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\Properties;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\PropertyResourceAssembler;

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

        $propAssembler = new PropertyResourceAssembler($this->router);
        $propAssembler->setVariable('property', $this->getVariable('property'));
        $propAssembler->setRootResource($this->getRootResource());

        $resource = $propAssembler->assemble(['address']);
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
        $property = $this->getVariable('property');
        $links    = $this->getVariable('links');
        $links['property:images'] = new Link($this->router->generate('pp_property_properties_images_get_all', ['propId' => $property->getId()]));

        return $links;
    }
}
