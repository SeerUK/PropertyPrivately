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
     * Find active sales of a given user
     *
     * @param  integer $id
     * @return array
     */
    public function findActiveByUserId($id)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $date = new \DateTime();

        $query = $qb
            ->select('s')
            ->from('PropertyPrivatelyPropertyBundle:Sale', 's')
            ->innerJoin('s.property', 'p')
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
     * Find any sales by user id
     *
     * @param  integer $id
     * @return array
     */
    public function findByUserId($id)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();

        $query = $qb
            ->select('s')
            ->from('PropertyPrivatelyPropertyBundle:Sale', 's')
            ->innerJoin('s.property', 'p')
            ->innerJoin('p.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Find active sale by given id
     *
     * @param  integer $id
     * @return array
     */
    public function findOneActiveById($id)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $date = new \DateTime();

        $query = $qb
            ->select('s')
            ->from('PropertyPrivatelyPropertyBundle:Sale', 's')
            ->where('s.id = :id')
            ->andWhere('s.start <= :now')
            ->andWhere('s.end > :now')
            ->andWhere('s.enabled = 1')
            ->setParameter('id', $id)
            ->setParameter('now', $date)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Find one active sale by given sale id and property id
     *
     * @param  integer $saleId
     * @param  integer $propertyId
     * @return Sale
     */
    public function findOneActiveBySaleIdAndPropertyId($saleId, $propertyId)
    {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $date = new \DateTime();

        $query = $qb
            ->select('s')
            ->from('PropertyPrivatelyPropertyBundle:Sale', 's')
            ->innerJoin('s.property', 'p')
            ->where('s.id = :saleId')
            ->andWhere('p.id = :propertyId')
            ->andWhere('s.start <= :now')
            ->andWhere('s.end > :now')
            ->andWhere('s.enabled = 1')
            ->setParameter('saleId', $saleId)
            ->setParameter('propertyId', $propertyId)
            ->setParameter('now', $date)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Find active sales of a given property
     *
     * @param  integer $id
     * @return array
     */
    public function findPotentiallyActiveByPropertyId($id)
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
