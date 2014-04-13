<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Generator;

/**
 * Token Generator
 */
class TokenGenerator
{
    /**
     * Generate a token
     *
     * @return string
     */
    public function generate()
    {
        return hash('sha256', uniqid(rand() + microtime(), true));
    }
}
