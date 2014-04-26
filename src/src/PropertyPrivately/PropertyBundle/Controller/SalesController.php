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
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;

/**
 * Sales Controller
 */
class SalesController extends RestController
{
    public function directoryAction()
    {
        $assembler = $this->get('pp_property.resource_assembler.sales.directory_assembler');

        return new JsonResponse($assembler->assemble());
    }

    public function getAction($id)
    {
        $saleRepo = $this->get('pp_property.sale_repository');
        $sale     = $saleRepo->findOneActiveById($id);

        if ( ! $sale) {
            throw new NotFoundHttpException(ErrorMessages::SALE_NOT_FOUND);
        }

        $assembler = $this->get('pp_property.resource_assembler.sales.get_assembler');
        $assembler->setVariable('sale', $sale);

        return new JsonResponse($assembler->assemble());
    }
}
