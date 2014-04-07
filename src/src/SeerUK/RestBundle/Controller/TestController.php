<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SeerUK\RestBundle\Hal\HalLink;

/**
 * Test Controller
 */
class TestController extends Controller
{
    public function testAction()
    {
        $router   = $this->get('router');
        $request  = $this->get('request');
        $response = $this->get('seer_uk_rest.hal_response_json');
        $response->addLink(new HalLink($router->generate($request->get('_route'))), 'next');
        $response->setData(array(
            'oops' => 'heh'
        ));

        return $response;
    }
}
