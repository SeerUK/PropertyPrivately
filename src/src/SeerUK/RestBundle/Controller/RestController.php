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

/**
 * Rest Controller
 */
class RestController extends Controller
{
    /**
     * Make an internal sub-request to the given route name, with parameters
     *
     * @param  string  $route
     * @param  array   $path
     * @param  integer $statusCode
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function forwardToRoute($route, array $path, $statusCode)
    {
        $routes     = $this->get('router')->getRouteCollection();
        $controller = $routes->get($route)->getDefaults()['_controller'];

        $response = $this->forward($controller, $path);
        $response->setStatusCode($statusCode);
        $response->headers->set('Location', $this->generateUrl($route, $path, true));

        return $response;
    }
}
