<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Rest Controller
 */
class RestController extends Controller
{
    /**
     * Make an internal master-request to the given route name, with parameters
     *
     * @param  string  $route
     * @param  array   $path
     * @param  integer $statusCode
     * @param  array   $requestHeaders
     * @param  array   $query
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function createInternalRequest($route, array $path = array(), $statusCode = 200,
        array $requestHeaders = null, array $query = array())
    {
        $routes = $this->get('router')->getRouteCollection();

        if ( ! $routes->get($route)) {
            throw new RouteNotFoundException(sprintf(
                'No route found with name "%s"',
                $route
            ));
        }

        $path['_controller'] = $routes->get($route)->getDefaults()['_controller'];

        // Create internal-request
        $subRequest = $this->get('request')->duplicate($query, null, $path);
        $subRequest->headers->add($requestHeaders);

        // Get the response
        $response = $this->get('http_kernel')->handle($subRequest);
        $response->setStatusCode($statusCode);

        return $response;
    }

    /**
     * Simplified call to create an internal request, conforming to RESTs POST
     * expectations
     *
     * @param  string $route
     * @param  array  $path
     * @param  array  $requestHeaders
     * @return Response
     */
    public function getPostResponse($route, array $path = array(), array $requestHeaders = array())
    {
        $response = $this->createInternalRequest($route, $path, Response::HTTP_CREATED, $requestHeaders);
        $response->headers->set('Location', $this->generateUrl($route, $path, true));

        return $response;
    }


    /**
     * Simplified call to create an internal request, conforming to RESTs PATCH
     * expectations
     *
     * @param  string $route
     * @param  array  $path
     * @param  array  $requestHeaders
     * @return Response
     */
    public function getPatchResponse($route, array $path = array(), array $requestHeaders = array())
    {
        return $this->createInternalRequest($route, $path, Response::HTTP_OK, $requestHeaders);
    }
}
