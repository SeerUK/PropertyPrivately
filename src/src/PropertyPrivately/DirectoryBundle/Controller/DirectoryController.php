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
use SeerUK\RestBundle\Hal\Link\LinkCollection as HalLinkCollection;
use SeerUK\RestBundle\Hal\EmbeddedResource\EmbeddedResource as HalEmbeddedResource;

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


        $collection = new HalLinkCollection();
        $collection->add(new HalLink('test'), 'test');

        $resource = new HalEmbeddedResource('test');
        $resource->setLinkCollection($collection);
        // $resource->setEmbeddedResourceCollection($collection);
        // $resource->setVariable('name', $value)

        $response->addEmbeddedResource($resource, 'test', true);
        $response->addEmbeddedResource($resource, 'test', true);
        $response->addEmbeddedResource($resource, 'test', true);

        return $response;
    }
}
