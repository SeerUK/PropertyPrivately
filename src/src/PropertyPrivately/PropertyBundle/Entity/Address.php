<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\PropertyBundle\Entity\Property;

/**
 * PropertyPrivately\PropertyBundle\Entity\Address
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\PropertyBundle\Entity\Repository\AddressRepository")
 * @ORM\Table(name="PPProperty.Address")
 * @ORM\HasLifecycleCallbacks
 */
class Address implements ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="buildingName", type="string", length=50)
     * @Assert\Length(
     *  min="1",
     *  max="50",
     *  minMessage="Your building name must be at least {{ limit }} characters long.",
     *  maxMessage="Your building name cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="Your building name value is not a valid {{ type }}.")
     */
    private $buildingName;

    /**
     * @ORM\Column(name="address1", type="string", length=100)
     * @Assert\Length(
     *  min="3",
     *  max="100",
     *  minMessage="Your address line 1 must be at least {{ limit }} characters long.",
     *  maxMessage="Your address line 1 cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="Your address line 1 value is not a valid {{ type }}.")
     *
     * @Assert\NotBlank(groups={"CREATE"})
     */
    private $address1;

    /**
     * @ORM\Column(name="address2", type="string", length=50)
     * @Assert\Length(
     *  min="3",
     *  max="50",
     *  minMessage="Your address line 2 must be at least {{ limit }} characters long.",
     *  maxMessage="Your address line 2 cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="Your address line 2 value is not a valid {{ type }}.")
     */
    private $address2;

    /**
     * @ORM\Column(name="town", type="string", length=50)
     * @Assert\Length(
     *  min="1",
     *  max="50",
     *  minMessage="Your town must be at least {{ limit }} characters long.",
     *  maxMessage="Your town cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="Your town value is not a valid {{ type }}.")
     *
     * @Assert\NotBlank(groups={"CREATE"})
     */
    private $town;

    /**
     * @ORM\Column(name="postcode", type="string", length=7)
     * @Assert\Length(
     *  min="5",
     *  max="7",
     *  minMessage="Your postcode must be at least {{ limit }} characters long.",
     *  maxMessage="Your postcode cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="Your postcode value is not a valid {{ type }}.")
     *
     * @Assert\NotBlank(groups={"CREATE"})
     */
    private $postcode;

    /**
     * @ORM\OneToOne(targetEntity="Property", inversedBy="address")
     * @ORM\JoinColumn(name="propertyId", referencedColumnName="id")
     */
    private $property;


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
     * Set buildingName
     *
     * @param string $buildingName
     * @return Address
     */
    public function setBuildingName($buildingName)
    {
        $this->buildingName = $buildingName;

        return $this;
    }

    /**
     * Get buildingName
     *
     * @return string
     */
    public function getBuildingName()
    {
        return $this->buildingName;
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
     * Set property
     *
     * @param  Property $property
     * @return Address
     */
    public function setProperty(Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return [
            'id'           => $this->id,
            'buildingName' => $this->buildingName,
            'address1'     => $this->address1,
            'address2'     => $this->address2,
            'town'         => $this->town,
            'postcode'     => $this->postcode
        ];
    }
}
