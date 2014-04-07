<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\EmbeddedResource;

use SeerUK\RestBundle\Hal\ResourceInterface;

/**
 * Hal Embedded Resource
 */
class EmbeddedResource implements \JsonSerializable, ResourceInterface
{
    public function __construct($href)
    {

    }

    public function JsonSerialize()
    {
        return array();
    }
}
