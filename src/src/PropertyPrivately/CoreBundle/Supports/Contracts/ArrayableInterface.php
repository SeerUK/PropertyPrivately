<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Supports\Contracts;

/**
 * Arrayable Interface
 */
interface ArrayableInterface
{
    /**
     * Get the instance as an array
     *
     * @return array
     */
    public function toArray();
}
