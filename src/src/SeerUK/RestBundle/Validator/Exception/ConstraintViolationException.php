<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Validator\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Validation Exception Interface
 */
class ConstraintViolationException extends HttpException
{
    /**
     * @var ConstraintViolationList
     */
    private $constraintViolations;

    /**
     * Prepare error message
     *
     * @param ConstraintViolationList $constraintViolations
     */
    public function __construct(ConstraintViolationList $constraintViolations, \Exception $previous = null)
    {
        parent::__construct(400, sprintf('Constraint violation, %d error(s).', count($constraintViolations)), $previous, array());

        $this->constraintViolations = $constraintViolations;
    }

    /**
     * Get all associated constraint violations
     *
     * @return ConstraintViolationList
     */
    public function getConstraintViolationList()
    {
        return $this->constraintViolations;
    }
}
