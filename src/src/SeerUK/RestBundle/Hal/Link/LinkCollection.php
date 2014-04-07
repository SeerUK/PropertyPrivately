<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\Link;

use SeerUK\RestBundle\Hal\CollectionInterface;
use SeerUK\RestBundle\Hal\ResourceInterface;

/**
 * HAL Link Collection
 */
class LinkCollection implements \JsonSerializable, CollectionInterface
{
    /**
     * @var array
     */
    private $links;

    /**
     * Set up class variables
     */
    public function __construct()
    {
        $this->links = array();
    }

    /**
     * Add a child HalLink
     *
     * @param  ResourceInterface $resource
     * @param  string            $name
     * @param  boolean           $append
     * @return HalLinkCollection
     */
    public function add(ResourceInterface $resource, $name, $append = null)
    {
        if ( ! $append) {
            $this->links[$name] = $resource;
        } else {
            $appended = array();

            if ($this->hasLink($name)) {
                if (is_array($this->getLink($name))) {
                    $appended = $this->getLink($name);
                } else {
                    $appended[] = $this->getLink($name);
                }
            }

            $appended[] = $resource;

            $this->links[$name] = $appended;
        }

        return $this;
    }

    /**
     * Get a specific link
     *
     * @param  string $name
     * @return array|HalLink
     */
    public function get($name)
    {
        return $this->links[$name];
    }

    /**
     * Get all links
     *
     * @return array
     */
    public function getAll()
    {
        return $this->links;
    }

    /**
     * Check if a specific link exists in collection
     *
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * Check if links exist in collection
     *
     * @return boolean
     */
    public function hasAny()
    {
        return (bool) $this->count();
    }

    /**
     * Remove a link
     *
     * @param  string $name
     * @return HalLinkCollection
     */
    public function remove($name)
    {
        unset($this->links[$name]);

        return $this;
    }

    /**
     * Clear links
     *
     * @return HalLinkCollection
     */
    public function clear()
    {
        $this->links = array();

        return $this;
    }

    /**
     * Count links
     *
     * @return integer
     */
    public function count()
    {
        return count($this->links);
    }

    /**
     * Select data to serialise
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getAll();
    }
}
