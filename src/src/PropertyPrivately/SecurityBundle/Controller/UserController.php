<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages;

/**
 * User Controller
 */
class UserController extends RestController
{
    public function getAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(ErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user      = $this->get('security.context')->getToken()->getUser();
        $assembler = $this->get('pp_security.resource_assembler.user.get_assembler');
        $assembler->setVariable('user', $user);

        return new JsonResponse($assembler->assemble());
    }
}
