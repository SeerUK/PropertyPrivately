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

use Doctrine\ORM\NoResultException;
use PropertyPrivately\CoreBundle\Entity\Repository\PersistentEntityRepository;
use PropertyPrivately\PropertyBundle\Entity\Image;

/**
 * Image Repository
 */
class ImageRepository extends PersistentEntityRepository
{
    /**
     * Find the next display order position by proprety ID, should be
     * used in a transaction, used internally.
     *
     * @param  integer $id
     * @return integer
     */
    private function findNextDisplayOrderPositionByPropertyId($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb
            ->select('i')
            ->from('PropertyPrivatelyPropertyBundle:Image', 'i')
            ->innerJoin('i.property', 'p')
            ->where('p.id = :id')
            ->orderBy('i.displayOrder', 'DESC')
            ->setParameter('id', $id);

        try {
            $image = $query->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();

            return $image->getDisplayOrder() + 1;
        } catch (NoResultException $e) {
            return 1;
        }
    }

    /**
     * @see PersistentEntityRepository::persist()
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

        $entity->setDisplayOrder($this->findNextDisplayOrderPositionByPropertyId(
            $entity->getProperty()->getId()
        ));

        return parent::persist($entity);
    }
}
