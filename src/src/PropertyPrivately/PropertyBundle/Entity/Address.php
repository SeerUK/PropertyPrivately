<?php

namespace PropertyPrivately\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 */
class Address
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $buildingname;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var string
     */
    private $address2;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $postcode;

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
     * Set buildingname
     *
     * @param string $buildingname
     * @return Address
     */
    public function setBuildingname($buildingname)
    {
        $this->buildingname = $buildingname;

        return $this;
    }

    /**
     * Get buildingname
     *
     * @return string 
     */
    public function getBuildingname()
    {
        return $this->buildingname;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return Address
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Address
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set town
     *
     * @param string $town
     * @return Address
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string 
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Address
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return Address
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
     * @return Address
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
