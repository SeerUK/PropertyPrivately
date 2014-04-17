<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Entity\Patcher;

/**
 * Patchable Entity Interface
 */
interface PatchableEntityInterface
{
    /**
     * Gets the patchable properties of an entity
     *
     * @return array
     */
    public function getPatchableProperties();
}
