<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Exception;

use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;

/**
 * Exception Wrapper
 */
class ExceptionWrapper extends \ArrayObject
{
    /**
     * @var integer|string
     */
    public $code;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $message;

    /**
     * @var array|\ArrayObject
     */
    public $errors;

    /**
     * @var array|\ArrayObject
     */
    public $previous;

    public function __construct(\Exception $exception)
    {
        $this->setFlags(self::STD_PROP_LIST);

        $this->type    = get_class($exception);
        $this->code    = $exception->getCode();
        $this->message = $exception->getMessage();

        $this->errors   = $this->getErrors($exception);
        $this->previous = $this->getPrevious($exception);
    }

    /**
     * @todo NYI
     */
    private function getErrors(\Exception $exception)
    {
        $errors = array();
        if ($exception instanceof ConstraintViolationException) {
            foreach ($exception->getValidationErrors() as $error) {
                $errors[] = array(
                    'code'    => $error->getCode(),
                    'field'   => $error->getRoot(),
                    'message' => $error->getMessage(),
                );
            }
        }

        return $errors;
    }


    /**
     * Gets all linked exceptions
     *
     * @param  \Exception $exception
     * @return array
     */
    private function getPrevious(\Exception $exception)
    {
        $exceptions = array();
        while ($exception = $exception->getPrevious()) {
            $exceptions[] = array(
                'code'    => $exception->getCode(),
                'type'    => get_class($exception),
                'message' => $exception->getMessage(),
            );
        }

        return $exceptions;
    }
}
