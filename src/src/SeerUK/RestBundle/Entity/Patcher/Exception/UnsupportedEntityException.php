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
 * Unsupported Entity Exception
 */
class UnsupportedEntityException extends \InvalidArgumentException
{
    public function __construct($entity, $expectedType, \Exception $previous = null)
    {
        parent::__construct(sprintf('Expected entity of type "%s", "%s" given', $expectedType, is_object($entity) ? get_class($entity) : gettype($entity)));
    }
}
