<?php

namespace PropertyPrivately\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 */
class Offer
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $offer;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $lastmodified;

    /**
     * @var \PropertyPrivately\PropertyBundle\Entity\Sale
     */
    private $saleid;

    /**
     * @var \PropertyPrivately\PropertyBundle\Entity\User
     */
    private $userid;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set offer
     *
     * @param integer $offer
     * @return Offer
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return integer 
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Offer
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return Offer
     */
    public function setLastmodified($lastmodified)
    {
        $this->lastmodified = $lastmodified;

        return $this;
    }

    /**
     * Get lastmodified
     *
     * @return \DateTime 
     */
    public function getLastmodified()
    {
        return $this->lastmodified;
    }

    /**
     * Set saleid
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\Sale $saleid
     * @return Offer
     */
    public function setSaleid(\PropertyPrivately\PropertyBundle\Entity\Sale $saleid = null)
    {
        $this->saleid = $saleid;

        return $this;
    }

    /**
     * Get saleid
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\Sale 
     */
    public function getSaleid()
    {
        return $this->saleid;
    }

    /**
     * Set userid
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\User $userid
     * @return Offer
     */
    public function setUserid(\PropertyPrivately\PropertyBundle\Entity\User $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\User 
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
