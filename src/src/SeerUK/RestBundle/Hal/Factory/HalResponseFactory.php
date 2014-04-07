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
use SeerUK\RestBundle\Hal\Response\HalJsonResponse;
use SeerUK\RestBundle\Hal\HalLink;

/**
 * Hal Response Factory
 */
class HalResponseFactory
{
    /**
     * Build a JSON response
     *
     * @return HalJsonResponse
     */
    public function buildJsonResponse(RequestStack $requestStack, Router $router)
    {
        $request  = $requestStack->getCurrentRequest();
        $response = new HalJsonResponse();
        $response->addLink(
            new HalLink($router->generate($request->get('_route')))
        , 'self');

        return $response;
    }
}
