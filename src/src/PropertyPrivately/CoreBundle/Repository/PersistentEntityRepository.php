<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Persistent Entity Repository
 */
class PersistentEntityRepository extends EntityRepository
{
    /**
     * Persist entity
     *
     * @param  mixed $entity
     * @return PersistentEntityRepository
     */
    public function persist($entity)
    {
        if ( ! $this->isEntitySupported($entity)) {
            $type = gettype($entity) === 'object' ? get_class($entity) : gettype($entity);
            throw new \InvalidArgumentException(
                __METHOD__ .
                ' expected an instance of "' . $this->getEntityName() . '". Received "' . $type . '"'
            );
        }

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        $this->clearCache();

        return $this;
    }

    /**
     * Update entity
     *
     * @param  mixed $entity
     * @return PersistentEntityRepository
     */
    public function update($entity)
    {
        if ( ! $this->isEntitySupported($entity)) {
            $type = gettype($entity) === 'object' ? get_class($entity) : gettype($entity);
            throw new \InvalidArgumentException(
                __METHOD__ .
                ' expected an instance of "' . $this->getEntityName() . '". Received "' . $type . '"'
            );
        }

        $em = $this->getEntityManager();
        $em->flush();

        $this->clearCache();

        return $this;
    }

    /**
     * Remove entity
     *
     * @param  mixed $entity
     * @return PersistentEntityRepository
     */
    public function remove($entity)
    {
        if ( ! $this->isEntitySupported($entity)) {
            $type = gettype($entity) === 'object' ? get_class($entity) : gettype($entity);
            throw new \InvalidArgumentException(
                __METHOD__ .
                ' expected an instance of "' . $this->getEntityName() . '". Received "' . $type . '"'
            );
        }

        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();

        $this->clearCache();

        return $this;
    }

    /**
     * Clear entire result cache to avoid entity ghosts
     *
     * @return PersistentEntityRepository
     */
    protected function clearCache()
    {
        $cd =  $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
        $cd->deleteAll();

        return $this;
    }

    /**
     * Is the given entity supported by this repository?
     *
     * @param  mixed   $entity
     * @return boolean
     */
    public function isEntitySupported($entity)
    {
        return (get_class($entity) === $this->getEntityName())
            ? true
            : false;
    }
}
