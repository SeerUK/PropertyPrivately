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

/**
 * HAL Link Collection
 */
class HalLinkCollection implements \JsonSerializable
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
     * @param  HalLink $child
     * @param  string            $name
     * @param  boolean           $append
     * @return HalLinkCollection
     */
    public function addLink(HalLink $child, $name, $append = null)
    {
        if ( ! $append) {
            $this->links[$name] = $child;
        } else {
            $temp = array();

            if ($this->hasLink($name)) {
                if (is_array($this->getLink($name))) {
                    $temp = $this->getLink($name);
                } else {
                    $temp[] = $this->getLink($name);
                }
            }

            $temp[] = $child;

            $this->links[$name] = $temp;
        }

        return $this;
    }

    /**
     * Get a specific link
     *
     * @param  string $name
     * @return array|HalLink
     */
    public function getLink($name)
    {
        return $this->links[$name];
    }

    /**
     * Get all links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Check if a specific link exists in collection
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasLink($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * Check if links exist in collection
     *
     * @return boolean
     */
    public function hasLinks()
    {
        return (bool) $this->countLinks();
    }

    /**
     * Remove a link
     *
     * @param  string $name
     * @return HalLinkCollection
     */
    public function removeLink($name)
    {
        unset($this->links[$name]);

        return $this;
    }

    /**
     * Clear links
     *
     * @return HalLinkCollection
     */
    public function clearLinks()
    {
        $this->links = array();

        return $this;
    }

    /**
     * Count links
     *
     * @return integer
     */
    public function countLinks()
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
        return $this->getLinks();
    }
}
