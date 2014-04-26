<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Resource\Assembler\PropertiesImages;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\PropertyBundle\Resource\Assembler\ImageResourceAssembler;

/**
 * Get All Resource Assembler
 */
class GetAllResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssemlber::assemble()
     */
    public function assemble(array $nested = array())
    {
        $images = $this->getVariable('images');
        $this->rootResource->setVariable('total', count($images));
        $this->rootResource->addLinks($this->assembleLinks());

        $imgAssembler = new ImageResourceAssembler($this->router);

        foreach ($images as $image) {
            $imgAssembler->setRootResource(new Resource());
            $imgAssembler->setVariable('image', $image);

            $this->rootResource->addResource('images', $imgAssembler->assemble(), true);
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

        $imgLink = new Link($this->generateRouteTemplate('pp_property_properties_images_get'));
        $imgLink->setName('Specific Image');
        $imgLink->setTemplated(true);

        $links['images:image'] = $imgLink;

        return $links;
    }
}
