<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity\Patcher;

use SeerUK\RestBundle\Entity\Patcher\AbstractEntityPatcher;
use SeerUK\RestBundle\Entity\Patcher\PatchableEntityInterface;
use SeerUK\RestBundle\Entity\Patcher\Exception\InvalidOperationException;

/**
 * User Patcher
 */
class UserPatcher extends AbstractEntityPatcher
{
    /**
     * @see AbstractEntityPatcher::addOperation()
     */
    protected function addOperation(PatchableEntityInterface $entity, $operation)
    {
        return $this->replaceOperation($entity, $operation);
    }

    /**
     * @see AbstractEntityPatcher::removeOperation()
     */
    protected function removeOperation(PatchableEntityInterface $entity, $operation)
    {
        $method = 'set' . ucfirst($operation->path);

        if ( ! method_exists($entity, $method)) {
            throw new InvalidOperationException(sprintf('Invalid path "%s".', $operation->path));
        }

        $entity->$method(null);

        return $entity;
    }

    /**
     * @see AbstractEntityPatcher::replaceOperation()
     */
    protected function replaceOperation(PatchableEntityInterface $entity, $operation)
    {
        $method = 'set' . ucfirst($operation->path);

        if ( ! method_exists($entity, $method)) {
            throw new InvalidOperationException(sprintf('Invalid path "%s".', $operation->path));
        }

        $entity->$method($operation->value);

        return $entity;
    }

    /**
     * @see AbstractEntityPatcher::moveOperation()
     */
    protected function moveOperation(PatchableEntityInterface $entity, $operation)
    {
        $entity = $this->copyOperation($entity, $operation);
        $entity = $this->removeOperation($entity, (object) ['path' => $operation->from]);

        return $entity;
    }

    /**
     * @see AbstractEntityPatcher::copyOperation()
     */
    protected function copyOperation(PatchableEntityInterface $entity, $operation)
    {
        $fromGetMethod = 'get' . ucfirst($operation->from);
        $pathSetMethod = 'set' . ucfirst($operation->path);

        if ( ! method_exists($entity, $pathSetMethod)) {
            throw new InvalidOperationException(sprintf('Invalid path "%s".', $operation->path));
        }

        if ( ! method_exists($entity, $fromGetMethod)) {
            throw new InvalidOperationException(sprintf('Invalid from "%s".', $operation->from));
        }

        $entity->$pathSetMethod($entity->$fromGetMethod());

        return $entity;
    }

    /**
     * @see AbstractEntityPatcher::supports()
     */
    protected function supports()
    {
        return 'PropertyPrivately\SecurityBundle\Entity\User';
    }
}
