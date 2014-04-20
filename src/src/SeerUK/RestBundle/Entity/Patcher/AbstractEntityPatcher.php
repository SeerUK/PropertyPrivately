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

use Symfony\Component\Validator\Validator;
use SeerUK\RestBundle\Entity\Patcher\Exception\InvalidOperationException;
use SeerUK\RestBundle\Entity\Patcher\Exception\UnsupportedEntityException;
use SeerUK\RestBundle\Entity\Patcher\Exception\UnsupportedOperationException;
use SeerUK\RestBundle\Entity\Patcher\PatchableEntityInterface;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;

/**
 * Abstract Entity Patcher
 *
 * Designed to conform with JSON PATCH
 * (http://tools.ietf.org/html/rfc6902)
 */
abstract class AbstractEntityPatcher
{
    /**
     * @var string
     */
    protected $lastError;

    /**
     * Process the patch request
     *
     * @param  PatchableEntityInterface $entity
     * @param  array                    $operations
     * @return PatchableEntityInterface
     */
    public function patch(PatchableEntityInterface $entity, array $operations)
    {
        if ( ! $this->isSupportedEntity($entity)) {
            throw new UnsupportedEntityException($entity, $this->supports());
        }

        return $this->applyOperations($entity, $operations);
    }

    /**
     * Apply the given operations to the entity
     *
     * @param  PatchableEntityInterface $entity
     * @param  array                    $operations
     * @return PatchableEntityInterface
     */
    protected function applyOperations(PatchableEntityInterface $entity, array $operations)
    {
        foreach ($operations as $operation) {
            if ( ! $this->isValidOperation($operation)) {
                throw new InvalidOperationException($this->getLastError());
            }

            if ( ! $this->isOperationSupportedByEntity($entity, $operation)) {
                throw new UnsupportedOperationException($entity, $operation);
            }

            $method = strtolower($operation->op) . 'Operation';
            $entity = $this->$method($entity, $operation);
        }

        return $entity;
    }

    /**
     * Validate an entity
     *
     * @param  PatchableEntityInterface $entity
     * @return array
     */
    protected function validate(PatchableEntityInterface $entity)
    {
        return $this->validator->validate($entity);
    }

    /**
     * Is this entity is supported by this patcher?
     *
     * @param  PatchableEntityInterface $entity
     * @return boolean
     */
    protected function isSupportedEntity(PatchableEntityInterface $entity)
    {
        return $this->supports() === get_class($entity);
    }

    /**
     * Is this operation supported by this entity?
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return boolean
     */
    protected function isOperationSupportedByEntity(PatchableEntityInterface $entity, $operation)
    {
        if ('copy' === $operation->op || 'move' === $operation) {
            if ( ! in_array($operation->from, $entity->getPatchableProperties())) {
                return false;
            }
        }

        return in_array($operation->path, $entity->getPatchableProperties())
            ? true
            : false;
    }

    /**
     * Is this operation valid?
     *
     * @param  stdClass $operation
     * @return boolean
     */
    protected function isValidOperation($operation)
    {
        if ( ! isset($operation->op) || ! isset($operation->path)) {
            $this->setLastError('Invalid operation definition.');
            return false;
        }

        if ( ! method_exists($this, strtolower($operation->op) . 'Operation')) {
            $this->setLastError(sprintf('Invalid operation "%s".', $operation->op));
            return false;
        }

        switch ($operation->op) {
            case 'add':
            case 'replace':
            case 'test':
                if ( ! isset($operation->value)) {
                    $this->setLastError(sprintf('Invalid "%s" operation, no "value" set.', $operation->op));
                    return false;
                }
                break;
            case 'move':
            case 'copy':
                if ( ! isset($operation->from)) {
                    $this->setLastError(sprintf('Invalid "%s" operation, no "from" set.', $operation->op));
                    return false;
                }
                break;
            case 'remove':
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Get last error
     *
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Set last error
     *
     * @param string $message
     */
    private function setLastError($message)
    {
        $this->lastError = $message;

        return $this;
    }

    /**
     * Execute 'add' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function addOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Execute 'remove' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function removeOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Execute 'replace' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function replaceOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Execute 'move' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function moveOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Execute 'copy' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function copyOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Execute 'test' operation (has no relevance to entities)
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    protected function testOperation(PatchableEntityInterface $entity, $operation)
    {
        return $entity;
    }

    /**
     * Return full class name of supported entity
     *
     * @return string
     */
    abstract protected function supports();
}
