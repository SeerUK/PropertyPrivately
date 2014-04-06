<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Model;

/**
 * Nestable Model Interface
 */
interface NestableModelInterface
{
    public function addChild(NestableModelInterface $child, $name, $append = null);
    public function getChild($name);
    public function getChildren();
    public function hasChild($name);
    public function hasChildren();
    public function removeChild($name);
    public function clearChildren();
    public function countChildren();
}
