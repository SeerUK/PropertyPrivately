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
     * @return JsonResponse
     */
    public function indexAction()
    {
        $router   = $this->get('router');
        $resource = $this->get('seer_uk_rest.hal_root_resource');
        $resource->addLink('pp:auth', new HalLink($router->generate('pp_security_authentication_post')));
        $resource->addLink('pp:applications', new HalLink($router->generate('pp_security_applications_directory')));
        $resource->addLink('pp:properties', new HalLink($router->generate('pp_property_properties_directory')));
        $resource->addLink('pp:sales', new HalLink($router->generate('pp_property_sales_directory')));
        $resource->addLink('pp:user', new HalLink($router->generate('pp_security_user_get')));
        $resource->addLink('pp:users', new HalLink($router->generate('pp_security_users_directory')));

        return new JsonResponse($resource);
    }
}
