<?php

namespace PropertyPrivately\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sale
 */
class Sale
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $price;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var \DateTime
     */
    private $lastmodified;

    /**
     * @var \PropertyPrivately\PropertyBundle\Entity\Property
     */
    private $propertyid;


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
     * Set price
     *
     * @param integer $price
     * @return Sale
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Sale
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Sale
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Sale
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Sale
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return Sale
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
     * Set propertyid
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\Property $propertyid
     * @return Sale
     */
    public function setPropertyid(\PropertyPrivately\PropertyBundle\Entity\Property $propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\Property 
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }
}
