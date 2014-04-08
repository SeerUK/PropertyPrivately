<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\Resource;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Link\LinkCollection;
use SeerUK\RestBundle\Hal\Resource\EmbeddedResourceCollection;
use SeerUK\RestBundle\Hal\Resource\ResourceInterface;

/**
 * Hal Resource
 */
class Resource implements \JsonSerializable, ResourceInterface
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
     * @var array
     */
    private $variables;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setEmbeddedResourceCollection(new EmbeddedResourceCollection());
        $this->setLinkCollection(new LinkCollection());
        $this->setVariables(array());
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
     * @return Resource
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
     * @return Resource
     */
    public function setLinkCollection(LinkCollection $collection)
    {
        $this->linkCollection = $collection;

        return $this;
    }

    /**
     * Add a link to the link collection in this resource
     *
     * @param  Link    $Link
     * @param  string  $name
     * @param  boolean $append
     * @return Resource
     */
    public function addLink(Link $link, $name, $append = null)
    {
        $this->linkCollection->add($link, $name, $append);

        return $this;
    }


    /**
     * Add an array of links to collection in this resource
     *
     * @param  array $links
     * @return Resource
     */
    public function addLinks(array $links)
    {
        foreach ($links as $rel => $link) {
            if ( ! $link instanceof Link) {
                $type = is_object($link) ? get_class($link) : gettype($link);
                throw new \InvalidArgumentException(
                    __METHOD__ . ': Expected SeerUK\RestBundle\Hal\Link\Link, but instead received "' . $type . '"'
                );
            }

            $this->addLink($link, $rel);
        }

        return $this;
    }

    /**
     * Remove a link from the link collection in this resource
     *
     * @param  string $name
     * @return Resource
     */
    public function removeLink($name)
    {
        $this->linkCollection->remove($name);

        return $this;
    }

    /**
     * Get a specific variable
     *
     * @param  string $name
     * @return mixed
     */
    public function getVariable($name)
    {
        return $this->variables[$name];
    }

    /**
     * Set a specific variable
     *
     * @param  string $name
     * @param  mixed  $value
     * @return Resource
     */
    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * Unset a specific variable
     *
     * @param  string $name
     * @return Resource
     */
    public function unsetVariable($name)
    {
        unset($this->variables[$name]);

        return $this;
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set variables
     *
     * @param  array $variables
     * @return Resource
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Return array representation of this resource
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $resource = $this->getVariables();

        if ($this->linkCollection->hasAny()) {
            $resource['_links'] = $this->linkCollection;
        }

        if ($this->embeddedResourceCollection->hasAny()) {
            $resource['_embedded'] = $this->embeddedResourceCollection;
        }

        return $resource;
    }
}
