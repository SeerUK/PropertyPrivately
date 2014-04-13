<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Exception;

/**
 * Missing Mandatory Parameter(s) Exception
 */
class MissingMandatoryParametersException extends \InvalidArgumentException
{
    public function __construct($message = 'Missing mandatory parameter(s).', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
