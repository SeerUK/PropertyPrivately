<?php

/**
 * Seer UK REST Bundle
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
use SeerUK\RestBundle\Model\VariableModelInterface;

/**
 * Hal Resource
 */
class Resource implements \JsonSerializable, ResourceInterface, VariableModelInterface
{
    /**
     * @var array
     */
    private $links;

    /**
     * @var array
     */
    private $resources;

    /**
     * @var array
     */
    private $variables;

    /**
     * Constructor
     */
    public function __construct(array $variables = array())
    {
        $this->links     = array();
        $this->resources = array();
        $this->variables = $variables;
    }

    /**
     * Add a resource
     *
     * @param string   $name
     * @param Resource $resource
     */
    public function addResource($name, Resource $resource, $append = null)
    {
        if ( ! $append) {
            $this->resources[$name] = $resource;
        } else {
            $appended = array();

            if ($this->hasResource($name)) {
                if (is_array($this->getResource($name))) {
                    $appended = $this->getResource($name);
                } else {
                    $appended[] = $this->getResource($name);
                }
            }

            $appended[] = $resource;

            $this->resources[$name] = $appended;
        }

        return $this;
    }

    /**
     * Add resources
     *
     * @param  array $resources
     * @return Resource
     */
    public function addResources(array $resources)
    {
        foreach ($resources as $rel => $resource) {
            $this->addResource($rel, $resource);
        }

        return $this;
    }

    /**
     * Get a resource
     *
     * @param  string $name
     * @return Resource
     */
    public function getResource($name)
    {
        return $this->resources[$name];
    }

    /**
     * Get resources
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Check if a resource exists
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasResource($name)
    {
        return isset($this->resources[$name]);
    }

    /**
     * Check if resources exist
     *
     * @return boolean
     */
    public function hasResources()
    {
        return (bool) $this->countResources();
    }

    /**
     * Remove a resource
     *
     * @param  string $name
     * @return Resource
     */
    public function removeResource($name)
    {
        unset($this->resources[$name]);

        return $this;
    }

    /**
     * Clear resources
     *
     * @return Resource
     */
    public function clearResources()
    {
        $this->resources = array();

        return $this;
    }

    /**
     * Count resources
     *
     * @return integer
     */
    public function countResources()
    {
        return count($this->resources);
    }

    /**
     * Add a link
     *
     * @param  string  $name
     * @param  Link    $link
     * @param  boolean $append
     * @return Resource
     */
    public function addLink($name, Link $link, $append = null)
    {
        if ( ! $append) {
            $this->links[$name] = $link;
        } else {
            $appended = array();

            if ($this->hasLink($name)) {
                if (is_array($this->getLink($name))) {
                    $appended = $this->getLink($name);
                } else {
                    $appended[] = $this->getLink($name);
                }
            }

            $appended[] = $link;

            $this->links[$name] = $appended;
        }

        return $this;
    }

    /**
     * Add links
     *
     * @param  array $links
     * @return Resource
     */
    public function addLinks(array $links)
    {
        foreach ($links as $rel => $link) {
            $this->addLink($rel, $link);
        }

        return $this;
    }

    /**
     * Get a link
     *
     * @param  string $name
     * @return Link
     */
    public function getLink($name)
    {
        return $this->links[$name];
    }

    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Check if link exists
     *
     * @param  string $name
     * @return boolean
     */
    public function hasLink($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * Check if links exist
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
     * @return Resource
     */
    public function removeLink($name)
    {
        unset($this->links[$name]);

        return $this;
    }

    /**
     * Clear links
     *
     * @return Resource
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
     * @see VariableModelInterface::getVariable()
     */
    public function getVariable($name)
    {
        return $this->variables[$name];
    }

    /**
     * @see VariableModelInterface::getVariables()
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @see VariableModelInterface::setVariable()
     */
    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @see VariableModelInterface::setVariables()
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @see VariableModelInterface::unsetVariable()
     */
    public function unsetVariable($name)
    {
        unset($this->variables[$name]);

        return $this;
    }

    /**
     * @see VariableModelInterface::clearVariables()
     */
    public function clearVariables()
    {
        $this->variables = array();

        return $this;
    }

    /**
     * @see VariableModelInterface::countVariables()
     */
    public function countVariables()
    {
        return count($this->variables);
    }

    /**
     * Return array representation of this resource
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $resource = $this->getVariables();

        if ($this->hasLinks()) {
            $resource['_links'] = $this->getLinks();
        }

        if ($this->hasResources()) {
            $resource['_embedded'] = $this->getResources();
        }

        return $resource;
    }
}
