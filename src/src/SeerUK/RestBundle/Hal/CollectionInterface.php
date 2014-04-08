<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal;

use SeerUK\RestBundle\Hal\Resource\ResourceInterface;

/**
 * Collection Interface
 */
interface CollectionInterface
{
    public function add(ResourceInterface $resource, $name, $append = null);
    public function get($name);
    public function getAll();
    public function has($name);
    public function hasAny();
    public function remove($name);
    public function clear();
    public function count();
}
