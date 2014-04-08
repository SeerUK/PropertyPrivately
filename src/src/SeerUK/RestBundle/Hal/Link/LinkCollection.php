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
use SeerUK\RestBundle\Hal\Link\Link;

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
     * Constructor
     */
    public function __construct()
    {
        $this->links = array();
    }

    /**
     * Add a child Link
     *
     * @param  ResourceInterface $resource
     * @param  string            $name
     * @param  boolean           $append
     * @return LinkCollection
     */
    public function add(ResourceInterface $resource, $name, $append = null)
    {
        if ( ! $resource instanceof Link) {
            throw new \InvalidArgumentException(
                __METHOD__ . ': Expected SeerUK\RestBundle\Hal\Link\Link, but got "' . get_class($resource) . '"'
            );
        }

        if ( ! $append) {
            $this->links[$name] = $resource;
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

            $this->links[$name] = $appended;
        }

        return $this;
    }

    /**
     * Get a specific link
     *
     * @param  string $name
     * @return array|Link
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
     * @return LinkCollection
     */
    public function remove($name)
    {
        unset($this->links[$name]);

        return $this;
    }

    /**
     * Clear links
     *
     * @return LinkCollection
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
