<?php

namespace PropertyPrivately\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 */
class Image
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $path;

    /**
     * @var integer
     */
    private $displayorder;

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
     * Set description
     *
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set displayorder
     *
     * @param integer $displayorder
     * @return Image
     */
    public function setDisplayorder($displayorder)
    {
        $this->displayorder = $displayorder;

        return $this;
    }

    /**
     * Get displayorder
     *
     * @return integer 
     */
    public function getDisplayorder()
    {
        return $this->displayorder;
    }

    /**
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return Image
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
     * @return Image
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
