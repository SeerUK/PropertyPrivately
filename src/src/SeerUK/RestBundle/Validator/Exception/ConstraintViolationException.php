<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Validator\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Validation Exception Interface
 */
class ConstraintViolationException extends \RuntimeException
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
    public function __construct(ConstraintViolationList $constraintViolations)
    {
        parent::__construct(sprintf('Constraint violation, %d error(s).', count($constraintViolations)));

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
