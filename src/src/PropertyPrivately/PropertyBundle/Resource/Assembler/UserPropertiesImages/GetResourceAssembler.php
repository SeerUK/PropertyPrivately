<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\UserPropertiesImages;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\ImageResourceAssembler;

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

        $imgAssembler = new ImageResourceAssembler($this->router);
        $imgAssembler->setVariable('image', $this->getVariable('image'));
        $imgAssembler->setRootResource($this->getRootResource());

        $resource = $imgAssembler->assemble(['property']);
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
        $property = $this->getVariable('image')->getProperty();
        $links    = $this->getVariable('links');
        $links['image:property'] = new Link($this->router->generate('pp_property_user_properties_get', ['id' => $property->getId()]));

        return $links;
    }
}
