<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\Factory;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Link\LinkCollection;

/**
 * Hal Response Factory
 */
class RootResourceFactory
{
    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor
     *
     * @param RequestStack $requestStack
     * @param Router       $router
     */
    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router  = $router;
    }

    /**
     * Build a JSON response
     *
     * @return HalJsonResponse
     */
    public function build()
    {
        $resource = new Resource();
        $resource->setLinkCollection(new LinkCollection());
        $resource->addLink(new Link($this->request->getRequestUri()), 'self');

        return $resource;
    }
}
