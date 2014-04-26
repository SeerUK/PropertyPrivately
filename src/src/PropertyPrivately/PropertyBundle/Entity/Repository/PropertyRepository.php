<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Entity\Repository;

use PropertyPrivately\CoreBundle\Entity\Repository\PersistentEntityRepository;

/**
 * Property Repository
 */
class PropertyRepository extends PersistentEntityRepository
{
    public function findWithActiveSaleByUserId($id)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $date = new \DateTime();

        $query = $qb
            ->select('p')
            ->from('PropertyPrivatelyPropertyBundle:Property', 'p')
            ->innerJoin('p.sales', 's')
            ->innerJoin('p.user', 'u')
            ->where('u.id = :id')
            ->andWhere('s.start <= :now')
            ->andWhere('s.end > :now')
            ->andWhere('s.enabled = 1')
            ->setParameter('id', $id)
            ->setParameter('now', $date)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Get supported entity
     *
     * @return string
     */
    protected function getEntityName()
    {
        return 'PropertyPrivately\PropertyBundle\Entity\Property';
    }
}
