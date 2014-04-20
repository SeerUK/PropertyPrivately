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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;

/**
 * Users Controller
 */
class UsersController extends RestController
{
    public function directoryAction()
    {
        $assembler = $this->get('pp_security.resource_assembler.users.directory_assembler');

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($username)
    {
        $userRepo = $this->get('pp_security.user_repository');
        $user     = $userRepo->findOneBy(array(
            'username' => $username
        ));

        if ( ! $user) {
            throw new NotFoundHttpException('User not found.');
        }

        $assembler = $this->get('pp_security.resource_assembler.users.get_assembler');
        $assembler->setVariable('user', $user);

        return new JsonResponse($assembler->assemble());
    }
}
