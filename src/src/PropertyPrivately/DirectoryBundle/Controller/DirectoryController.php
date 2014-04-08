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
use Symfony\Component\HttpFoundation\JsonResponse;
use SeerUK\RestBundle\Hal\Link\Link as HalLink;
use SeerUK\RestBundle\Hal\Resource\Resource as HalResource;

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
        $resource = $this->get('seer_uk_rest.hal_root_resource');

        // Generate directory index links
        $links = array();

        $links['pp:embeddedTest'] = new HalLink($router->generate('property_privately_directory_embedded_test'));
        $links['pp:properties']   = new HalLink($router->generate('property_privately_directory_index'));
        $links['pp:users']        = new HalLink($router->generate('property_privately_directory_index'));

        $resource->addLinks($links);

        return new JsonResponse($resource);
    }

    public function embeddedTestAction()
    {
        $router   = $this->get('router');
        $resource = $this->get('seer_uk_rest.hal_root_resource');
        $resource->addLink(new HalLink($router->generate('property_privately_directory_embedded_test')), 'first');
        $resource->addLink(new HalLink($router->generate('property_privately_directory_embedded_test')), 'last');

        $resource->setVariable('embedded', $resource->getEmbeddedResourceCollection()->count());
        $resource->setVariable('total', 0); // You'd really get this from the DB

        return new JsonResponse($resource);
    }
}
