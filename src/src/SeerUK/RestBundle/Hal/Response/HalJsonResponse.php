<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\Response;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResourceCollection;
use SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResource;
use SeerUK\RestBundle\Hal\Link\LinkCollection;
use SeerUK\RestBundle\Hal\Link\Link;

/**
 * HAL JSON Response
 */
class HalJsonResponse extends JsonResponse
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
    private $rawData;

    /**
     * Constructor
     *
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {
        $this->embeddedResourceCollection = new EmbeddedResourceCollection();
        $this->linkCollection             = new LinkCollection();

        parent::__construct('', $status, $headers);

        if (null !== $data) {
            $this->setData($data);
        }
    }

    /**
     * Set data
     *
     * @param  array $data
     * @return HalJsonResponse
     */
    public function setData($data = array())
    {
        $this->rawData = $data;

        return $this->updateContent();
    }

    /**
     * Update content, preserving existing data if any
     *
     * @return HalJsonResponse
     */
    public function updateContent()
    {
        if ($this->linkCollection->hasAny()) {
            $this->rawData['_links'] = $this->linkCollection;
        }

        if ($this->embeddedResourceCollection->hasAny()) {
            $this->rawData['_embedded'] = $this->embeddedResourceCollection;
        }

        return parent::setData($this->rawData);
    }

    /**
     * Add a HAL link
     *
     * @param  Link    $link
     * @param  string  $name
     * @param  boolean $append
     * @return HalJsonResponse
     */
    public function addLink(Link $link, $name, $append = null)
    {
        $this->linkCollection->add($link, $name, $append);

        return $this->updateContent();
    }

    /**
     * Add HAL links to link collection
     *
     * @param  array $links
     * @return HalJsonResponse
     */
    public function addLinks(array $links)
    {
        foreach ($links as $rel => $link) {
            if ( ! $link instanceof Link) {
                $type = is_object($link) ? get_class($link) : gettype($link);
                throw new \InvalidArgumentException(
                    __METHOD__ . ': Expected SeerUK\RestBundle\Hal\Link\Link, but got "' . $type . '"'
                );
            }

            $this->addLink($link, $rel);
        }

        return $this;
    }

    /**
     * Add an embedded resource
     *
     * @param  EmbeddedResource $link
     * @param  string           $name
     * @param  boolean          $append
     * @return HalJsonResponse
     */
    public function addEmbeddedResource(EmbeddedResource $resource, $name, $append = null)
    {
        $this->embeddedResourceCollection->add($resource, $name, $append);

        return $this->updateContent();
    }

    /**
     * Add embedded resources to embedded resource collection
     *
     * @param  array $links
     * @return HalJsonResponse
     */
    public function addEmbeddedResources(array $resources)
    {
        foreach ($resources as $rel => $resource) {
            if ( ! $resource instanceof EmbeddedResource) {
                $type = is_object($resource) ? get_class($resource) : gettype($resource);
                throw new \InvalidArgumentException(
                    __METHOD__ . ': Expected SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResource, but got "' . $type . '"'
                );
            }

            $this->addEmbeddedResource($resource, $rel);
        }

        return $this;
    }
}
