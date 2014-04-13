<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Exception;

/**
 * Bad Credential(s) exception
 */
class BadCredentialsException extends \InvalidArgumentException
{
    public function __construct($message = 'Bad credentials.', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
