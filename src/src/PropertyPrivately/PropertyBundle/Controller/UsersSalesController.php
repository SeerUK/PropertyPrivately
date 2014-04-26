<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

/**
 * Users Sales Controller
 */
class UsersSalesController extends RestController
{
    public function getAllAction($username)
    {
        $userRepo = $this->get('pp_security.user_repository');
        $user     = $userRepo->findOneBy([
            'username' => $username
        ]);

        if ( ! $user) {
            throw new NotFoundHttpException(SecurityErrorMessages::USER_NOT_FOUND);
        }

        $saleRepo  = $this->get('pp_property.sale_repository');

        if ( ! $this->getUser() || $user->getId() !== $this->getUser()->getId()) {
            $sales = $saleRepo->findActiveByUserId($user->getId());
        } else {
            $sales = $saleRepo->findByUserId($user->getId());
        }

        $assembler = $this->get('pp_property.resource_assembler.users_sales.get_all_assembler');
        $assembler->setVariable('sales', $sales);

        return new JsonResponse($assembler->assemble());
    }
}
