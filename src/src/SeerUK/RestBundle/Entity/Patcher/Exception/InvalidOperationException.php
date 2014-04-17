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
 * Invalid Operation Exception
 */
class InvalidOperationException extends \OutOfBoundsException
{
    public function __construct($message = 'Invalid operation.', \Exception $previous = null)
    {
        parent::__construct($message);
    }
}
