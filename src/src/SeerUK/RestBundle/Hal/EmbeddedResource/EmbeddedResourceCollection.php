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

use SeerUK\RestBundle\Hal\CollectionInterface;
use SeerUK\RestBundle\Hal\ResourceInterface;
use SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResource;

/**
 * Embedded Resource Collection
 */
class EmbeddedResourceCollection implements \JsonSerializable, CollectionInterface
{
    /**
     * @var array
     */
    private $resources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resources = array();
    }

    /**
     * Add a child resource
     *
     * @param  ResourceInterface $resource
     * @param  string            $name
     * @param  boolean           $append
     * @return EmbeddedResourceCollection
     */
    public function add(ResourceInterface $resource, $name, $append = null)
    {
        if ( ! $resource instanceof EmbeddedResource) {
            throw new \InvalidArgumentException(
                __METHOD__ . ': Expected SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResource, but got "' . get_class($resource) . '"'
            );
        }

        if ( ! $append) {
            $this->resources[$name] = $resource;
        } else {
            $appended = array();

            if ($this->has($name)) {
                if (is_array($this->get($name))) {
                    $appended = $this->get($name);
                } else {
                    $appended[] = $this->get($name);
                }
            }

            $appended[] = $resource;

            $this->resources[$name] = $appended;
        }

        return $this;
    }

    /**
     * Get a specific embedded resource
     *
     * @param  string $name
     * @return array|EmbeddedResource
     */
    public function get($name)
    {
        return $this->resources[$name];
    }

    /**
     * Get all embedded resources
     *
     * @return array
     */
    public function getAll()
    {
        return $this->resources;
    }

    /**
     * Check if a specific embedded resource exists in collection
     *
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->resources[$name]);
    }

    /**
     * Check if embedded resources exist in collection
     *
     * @return boolean
     */
    public function hasAny()
    {
        return (bool) $this->count();
    }

    /**
     * Remove an embedded resource
     *
     * @param  string $name
     * @return EmbeddedResourceCollection
     */
    public function remove($name)
    {
        unset($this->resources[$name]);

        return $this;
    }

    /**
     * Clear embedded resources
     *
     * @return EmbeddedResourceCollection
     */
    public function clear()
    {
        $this->resources = array();

        return $this;
    }

    /**
     * Count embedded resources
     *
     * @return integer
     */
    public function count()
    {
        return count($this->resources);
    }

    public function jsonSerialize()
    {
        return $this->getAll();
    }
}
