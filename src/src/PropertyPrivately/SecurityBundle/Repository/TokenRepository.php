<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use PropertyPrivately\SecurityBundle\Entity\Token;

/**
 * Token Repository
 */
class TokenRepository extends EntityRepository
{
    /**
     * Persist Token
     *
     * @param  Token $entity
     * @return TokenRepository
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

        // Clear query cache so that subsequent requests for Gallery objects
        // don't return ghosts
        $cd = $em->getConfiguration()->getResultCacheImpl();
        $cd->deleteAll();

        return $this;
    }

    protected function getEntityName()
    {
        return 'PropertyPrivately\SecurityBundle\Entity\Token';
    }

    /**
     * Is the given entity supported by this repository?
     *
     * @param  mixed   $entity
     * @return boolean
     */
    public function isEntitySupported($entity)
    {
        return ($entity instanceof Token)
            ? true
            : false;
    }
}
