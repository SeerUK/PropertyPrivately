<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse;
use SeerUK\RestBundle\HttpFoundation\HalLinkCollection;
use SeerUK\RestBundle\HttpFoundation\HalLink;

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

        parent::__construct($data, $status, $headers);
    }

    public function setData($data = array())
    {
        $this->linkCollection->addLink(new HalLink('http://www.google.co.uk/'), 'test', false);
        $this->linkCollection->addLink(new HalLink('http://www.google.co.uk/'), 'test', true);
        $this->linkCollection->addLink(new HalLink('http://www.google.co.uk/'), 'test', true);
        $this->linkCollection->addLink(new HalLink('http://www.google.co.uk/'), 'test', true);
        $this->linkCollection->addLink(new HalLink('http://www.google.co.uk/'), 'test2', false);

        // var_dump($this->linkCollection);
        // var_dump(json_encode($this->linkCollection->toArray()));

        $data['_links'] = $this->linkCollection->toArray();

        parent::setData($data);

        // var_dump($this->getContent());
        // exit;

        return parent::setData($data);
    }

    public function addLink(HalLink $link)
    {

    }

    public function addLinks(array $links)
    {

    }
}
