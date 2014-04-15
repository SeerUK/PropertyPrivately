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
 * Token Repository
 */
class TokenRepository extends PersistentEntityRepository
{
    /**
     * Find one Token by token
     *
     * @param  string $token
     * @return PropertyPrivately\SecurityBundle\Entity\Token
     */
    public function findOneByToken($token)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t, u')
            ->leftJoin('t.user', 'u')
            ->where('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery();

        // $query->useResultCache(true, 120);

        return $query->getSingleResult();
    }
}
