<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Exception\Utils;

/**
 * Error Messages
 */
final class ErrorMessages
{
    const REQUIRE_AUTHENTICATED_FULLY       = 'You must be authenticated fully to access this resource.';
    const REQUIRE_AUTHENTICATED_ANONYMOUSLY = 'You must be authenticated anonymously to access this resource.';

    const MISSING_CREDENTIALS = 'Missing credentials.';
    const BAD_CREDENTIALS     = 'Bad credentials.';
}
