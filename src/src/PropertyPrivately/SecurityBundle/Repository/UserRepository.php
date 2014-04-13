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

use PropertyPrivately\CoreBundle\Repository\PersistentEntityRepository;

/**
 * User Repository
 */
class UserRepository extends PersistentEntityRepository
{
    /**
     * Find one user by username
     *
     * @param  string $username
     * @return PropertyPrivately\SecurityBundle\Entity\User
     */
    public function findOneByUsername($username)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();

        // $query->useResultCache(true, 120);

        return $query->getOneOrNullResult();
    }
}
