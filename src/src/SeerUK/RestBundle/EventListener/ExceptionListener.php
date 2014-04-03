<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Exception Listener
 */
class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $body      = array(
            'code'     => $exception->getCode(),
            'type'     => get_class($exception),
            'message'  => $exception->getMessage()
        );

        if ($previous = $exception->getPrevious()) {
            $body['previous'] = array();

            do {
                $body['previous'][] = array(
                    'code'    => $previous->getCode(),
                    'message' => $previous->getMessage(),
                );
            } while ($previous = $previous->getPrevious());
        }

        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // if ($exception instanceof RestfulExceptionInterface) {
        //     $body['errors'] = array();

        //     foreach ($exception->getErrors() as $error) {
        //         if ( ! $error instanceof ValidationErrorInterface) {
        //             continue;
        //         }

        //         $body['errors'][] = array(
        //             'code'    => $error->getCode(),
        //             'field'   => $error->getField(),
        //             'message' => $error->getMessage(),
        //         );
        //     }
        // }

        $response->setData($body);

        $event->setResponse($response);
    }
}
