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

use SeerUK\RestBundle\Hal\CollectionInterface;

/**
 * Embedded Resource Collection
 */
class EmbeddedResourceCollection implements \JsonSerializable, CollectionInterface
{
    public function jsonSerialize()
    {
        return array();
    }
}
