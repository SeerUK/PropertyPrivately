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
use PropertyPrivately\PropertyBundle\Entity\Sale;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * PropertyPrivately\PropertyBundle\Entity\Offer
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\PropertyBundle\Entity\Repository\OfferRepository")
 * @ORM\Table(name="PPProperty.Offer")
 * @ORM\HasLifecycleCallbacks
 */
class Offer implements ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="offer", type="integer")
     * @Assert\Type(type="integer", message="Your offer value is not a valid {{ type }}.")
     *
     * @Assert\NotBlank()
     */
    private $offer;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="Sale", fetch="EAGER", inversedBy="offers")
     * @ORM\JoinColumn(name="saleId", referencedColumnName="id")
     */
    private $sale;

    /**
     * @ORM\ManyToOne(targetEntity="PropertyPrivately\SecurityBundle\Entity\User", fetch="EAGER", inversedBy="properties")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $user;


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
     * Set sale
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\Sale $sale
     * @return Offer
     */
    public function setSale(Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\Sale
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set userid
     *
     * @param \PropertyPrivately\PropertyBundle\Entity\User $userid
     * @return Offer
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PropertyPrivately\PropertyBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return [
            'id'      => $this->id,
            'offer'   => $this->offer,
            'created' => $this->created->format(\DateTime::ISO8601)
        ];
    }
}
