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
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;
use SeerUK\RestBundle\Entity\Patcher\PatchableEntityInterface;

/**
 * Abstract Entity Patcher
 */
abstract class AbstractEntityPatcher
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

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
            // Throw UnsupportedEntityException
        }

        $entity = $this->applyOperation($entity, $operations);
        $errors = $this->validate($entity);

        if (count($errors) > 0) {
            throw new ConstraintViolationException($errors);
        }

        return $entity;
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
            $method = strtolower($operation->op) . 'Operation');

            if ( ! isset($operation->op) || ! isset($operation->path)
                || ! method_exists($this, $method) {
                // Throw InvalidOperationException
            }

            if ( ! $this->isOperationSupportedByEntity($entity, $operation)) {
                // Throw UnsupportedOperationException
            }

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
        return in_array($operation->path, $entity->getPatchableProperties())
            ? true
            : false;
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
     * Execute 'test' operation
     *
     * @param  PatchableEntityInterface $entity
     * @param  stdClass                 $operation
     * @return PatchableEntityInterface
     */
    abstract protected function testOperation(PatchableEntityInterface $entity, $operation);

    /**
     * Return full class name of supported entity
     *
     * @return string
     */
    abstract function supports();
}
