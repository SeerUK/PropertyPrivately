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
 * PropertyPrivately\PropertyBundle\Entity\Sale
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\PropertyBundle\Entity\Repository\SaleRepository")
 * @ORM\Table(name="PPProperty.Sale")
 */
class Sale implements ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="price", type="integer")
     */
    protected $price;

    /**
     * @ORM\Column(name="start", type="datetime")
     */
    protected $start;

    /**
     * @ORM\Column(name="end", type="datetime")
     */
    protected $end;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\Type(type="boolean", message="Your enabled value is not a valid {{ type }}.")
     *
     * @Assert\NotBlank(groups={"POST"})
     */
    protected $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="Property", fetch="EAGER", inversedBy="sales")
     * @ORM\JoinColumn(name="propertyId", referencedColumnName="id")
     */
    protected $property;


    public function __construct()
    {
        $this->start   = new \DateTime();
        $this->end     = new \DateTime();
        $this->created = new \DateTime();
        $this->enabled = true;
    }

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
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return (bool) $this->enabled;
    }

    /**
     * Set property
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\Property $property
     * @return Sale
     */
    public function setProperty(Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\Property
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
        return array(
            'id'          => $this->id,
            'price'       => $this->price,
            'start'       => $this->start->format(\DateTime::ISO8601),
            'end'         => $this->end->format(\DateTime::ISO8601),
            'created'     => $this->created->format(\DateTime::ISO8601),
            'enabled'     => $this->enabled
        );
    }
}
