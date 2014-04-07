<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\DirectoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SeerUK\RestBundle\Hal\Link\Link as HalLink;

/**
 * Directory Controller
 */
class DirectoryController extends Controller
{
    /**
     * Directory index
     *
     * @return HalJsonResponse
     */
    public function indexAction()
    {
        $router   = $this->get('router');
        $response = $this->get('seer_uk_rest.hal_response_json');

        $response->addLinks(array('test'));

        // Generate directory index links
        $links = array();

        $links['pp:embeddedTest'] = new HalLink($router->generate('property_privately_directory_embedded_test'));
        $links['pp:properties']   = new HalLink($router->generate('property_privately_directory_index'));
        $links['pp:users']        = new HalLink($router->generate('property_privately_directory_index'));

        $response->addLinks($links);

        return $response;
    }

    public function embeddedTestAction()
    {
        $response = $this->get('seer_uk_rest.hal_response_json');
        // $response->addEmbeddedResource();

        return $response;
    }
}
