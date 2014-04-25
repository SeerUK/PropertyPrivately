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
 * Sale Repository
 */
class SaleRepository extends PersistentEntityRepository
{
    /**
     * Find active sales of a given property
     *
     * @param  integer $id
     * @return array
     */
    public function findActiveByPropertyId($id)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $date = new \DateTime();

        $query = $qb
            ->select('s')
            ->from('PropertyPrivatelyPropertyBundle:Sale', 's')
            ->innerJoin('s.property', 'p')
            ->where('p.id = :id')
            ->andWhere('s.start <= :now')
            ->andWhere('s.end > :now')
            ->andWhere('s.enabled = 1')
            ->setParameter('id', $id)
            ->setParameter('now', $date)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Get supported entity name
     *
     * @return string
     */
    protected function getEntityName()
    {
        return 'PropertyPrivately\PropertyBundle\Entity\Sale';
    }
}
