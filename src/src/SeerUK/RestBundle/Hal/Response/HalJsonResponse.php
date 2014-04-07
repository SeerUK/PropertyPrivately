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
use SeerUK\RestBundle\Hal\HalLinkCollection;
use SeerUK\RestBundle\Hal\HalLink;

/**
 * HAL JSON Response
 */
class HalJsonResponse extends JsonResponse
{
    /**
     * @var HalLinkCollection
     */
    private $linkCollection;

    /**
     * @var array|\ArrayObject
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
        $this->linkCollection = new HalLinkCollection();
        // $this->embeddedCollection = new HalEmbeddedCollection();

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
        $this->rawData['_links']    = $this->linkCollection;
        $this->rawData['_embedded'] = array();

        return parent::setData($this->rawData);
    }

    /**
     * Add a HAL link
     *
     * @param  HalLink $link
     * @param  string  $name
     * @param  boolean $append
     * @return HalJsonResponse
     */
    public function addLink(HalLink $link, $name, $append = null)
    {
        $this->linkCollection->addLink($link, $name, $append);

        return $this->updateContent();
    }
}
