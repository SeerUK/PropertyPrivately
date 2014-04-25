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
use PropertyPrivately\PropertyBundle\Resource\Assembler\PropertyResourceAssembler;

/**
 * Image Resource Assembler
 */
class ImageResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssemlber::assemble()
     */
    public function assemble(array $nested = array())
    {
        $image = $this->getVariable('image');

        $this->rootResource->setVariables($image->toArray());
        $this->rootResource->setVariable('path', $image->getWebPath());
        $this->rootResource->setVariable('filename', $image->getPath());
        $this->rootResource->addLinks($this->assembleLinks());

        if (in_array('property', $nested)) {
            $propAssembler = new PropertyResourceAssembler($this->router);
            $propAssembler->setVariable('property', $image->getProperty());

            $this->rootResource->addResource('property', $propAssembler->assemble());
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
        $image    = $this->getVariable('image');
        $property = $image->getProperty();

        $links = array();
        $links['self'] = new Link($this->router->generate('pp_property_user_properties_images_get', ['propId' => $property->getId(), 'imageId' => $image->getId()]));

        return $links;
    }
}
