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
        $resource->addLink('pp:properties', new HalLink($router->generate('property_privately_directory_index')));
        $resource->addLink('pp:user', new HalLink($router->generate('pp_security_user_get')));
        $resource->addLink('pp:users', new HalLink($router->generate('property_privately_directory_user_test')));

        return new JsonResponse($resource);
    }

    public function userTestAction()
    {
        $assembler = $this->get('pp_directory.user_test_resource_assembler');

        return new JsonResponse($assembler->assemble());
    }
}
