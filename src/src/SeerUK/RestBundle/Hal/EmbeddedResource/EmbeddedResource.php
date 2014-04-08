<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\EmbeddedResource;

use SeerUK\RestBundle\Hal\ResourceInterface;
use SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResourceCollection;
use SeerUK\RestBundle\Hal\Link\LinkCollection;

/**
 * Hal Embedded Resource
 */
class EmbeddedResource implements \JsonSerializable, ResourceInterface
{
    /**
     * @var EmbeddedResourceCollection
     */
    private $embeddedResourceCollection;

    /**
     * @var LinkCollection
     */
    private $linkCollection;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get embedded resource collection
     *
     * @return EmbeddedResourceCollection
     */
    public function getEmbeddedResourceCollection()
    {
        return $this->embeddedResourceCollection;
    }

    /**
     * Set embedded resource collection
     *
     * @param  EmbeddedResourceCollection $collection
     * @return EmbeddedResource
     */
    public function setEmbeddedResourceCollection(EmbeddedResourceCollection $collection)
    {
        $this->embeddedResourceCollection = $collection;

        return $this;
    }

    /**
     * Get link collection
     *
     * @return LinkCollection
     */
    public function getLinkCollection()
    {
        return $this->linkCollection;
    }

    /**
     * Set link collection
     *
     * @param LinkCollection $collection
     * @return EmbeddedResource
     */
    public function setLinkCollection(LinkCollection $collection)
    {
        $this->linkCollection = $collection;

        return $this;
    }

    /**
     * Return array representation of this resource
     */
    public function jsonSerialize()
    {
        $resource = array();

        if (isset($this->linkCollection) && $this->linkCollection->hasAny()) {
            $resource['_links'] = $this->linkCollection;
        }

        if (isset($this->embeddedResourceCollection) && $this->embeddedResourceCollection->hasAny()) {
            $resource['_embedded'] = $this->embeddedResourceCollection;
        }

        return $resource;
    }
}
