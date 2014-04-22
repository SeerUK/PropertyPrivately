<?php

namespace PropertyPrivately\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 */
class Room
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     * @var integer
     */
    private $length;

    /**
     * @var \DateTime
     */
    private $lastmodified;

    /**
     * @var \PropertyPrivately\PropertyBundle\Entity\Property
     */
    private $propertyid;

    /**
     * @var \PropertyPrivately\PropertyBundle\Entity\Roomtype
     */
    private $roomtypeid;


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
     * Set width
     *
     * @param integer $width
     * @return Room
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Room
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return Room
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return Room
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
     * @return Room
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

    /**
     * Set roomtypeid
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\Roomtype $roomtypeid
     * @return Room
     */
    public function setRoomtypeid(\PropertyPrivately\PropertyBundle\Entity\Roomtype $roomtypeid = null)
    {
        $this->roomtypeid = $roomtypeid;

        return $this;
    }

    /**
     * Get roomtypeid
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\Roomtype 
     */
    public function getRoomtypeid()
    {
        return $this->roomtypeid;
    }
}
