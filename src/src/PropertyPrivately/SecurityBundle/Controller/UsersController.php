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
use SeerUK\RestBundle\Controller\RestController;

/**
 * Users Controller
 */
class UsersController extends RestController
{
    public function getAction($id)
    {
        $userRepo  = $this->get('pp_security.user_repository');
        $assembler = $this->get('pp_security.resource_assembler.users.get_assembler');
        $assembler->setVariable('user', $userRepo->findOneBy(array(
            'id' => $id
        )));

        return new JsonResponse($assembler->assemble());
    }
}
