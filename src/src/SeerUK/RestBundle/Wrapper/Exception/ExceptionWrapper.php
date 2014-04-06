<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Wrapper\Exception;

use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;

/**
 * Exception Wrapper
 */
class ExceptionWrapper
{
    /**
     * @var integer|string
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array|\ArrayObject
     */
    private $errors;

    /**
     * @var array|\ArrayObject
     */
    private $previous;

    public function __construct(\Exception $exception)
    {
        $this->type    = get_class($exception);
        $this->code    = $exception->getCode();
        $this->message = $exception->getMessage();

        $this->errors   = $this->setErrors($exception);
        $this->previous = $this->setPrevious($exception);
    }

    /**
     * Get code
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get previous exception
     *
     * @return array
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Sets all validation errors
     *
     * @param  \Exception $exception
     * @return array
     */
    public function setErrors(\Exception $exception)
    {
        $errors = array();
        if ($exception instanceof ConstraintViolationException) {
            foreach ($exception->getConstraintViolationList() as $error) {
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
     * Sets all linked exceptions
     *
     * @param  \Exception $exception
     * @return array
     */
    public function setPrevious(\Exception $exception)
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

    /**
     * Convert wrapped content to array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'code'     => $this->getCode(),
            'type'     => $this->getType(),
            'message'  => $this->getMessage(),
            'errors'   => $this->getErrors(),
            'previous' => $this->getPrevious(),
        );
    }
}
