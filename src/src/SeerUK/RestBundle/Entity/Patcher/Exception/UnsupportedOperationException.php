<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Entity\Patcher\Exception;

/**
 * Unsupported Operation Exception
 */
class UnsupportedOperationException extends \InvalidArgumentException
{
    public function __construct($entity, $operation, \Exception $previous = null)
    {
        parent::__construct(sprintf(
            'Entity "%s" does not support operation "%s" on path "%s".',
            is_object($entity) ? get_class($entity) : gettype($entity),
            $operation->op,
            $operation->path
        ));
    }
}
