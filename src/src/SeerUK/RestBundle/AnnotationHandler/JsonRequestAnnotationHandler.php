<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\AnnotationHandler;

use Symfony\Component\Routing\Route;
use Nelmio\ApiDocBundle\Extractor\HandlerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use SeerUK\RestBundle\Annotation\JsonRequest;

/**
 * JSON Request Annotation Handler
 */
class JsonRequestAnnotationHandler implements HandlerInterface
{
    /**
     * @see HandlerInterface::handle()
     */
    public function handle(ApiDoc $annotation, array $annotations, Route $route, \ReflectionMethod $method)
    {
        $html = $annotation->getDocumentation();

        foreach ($annotations as $annot) {
            if ($annot instanceof JsonRequest) {
                $html .= '<h4>JSON Request Body Example</h4><pre>' . json_encode($annot->content, JSON_PRETTY_PRINT) . '</pre>';
            }
        }

        $annotation->setDocumentation($html);
    }
}
