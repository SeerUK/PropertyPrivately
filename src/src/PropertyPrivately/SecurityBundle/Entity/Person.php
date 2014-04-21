<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * PropertyPrivately\SecurityBundle\Entity\Person
 *
 * @ORM\Entity
 * @ORM\Table(name="Person")
 */
class Person implements \JsonSerializable, ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @OneToOne(targetEntity="User", inversedBy="person")
     * @JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\Length(
     *  min="3",
     *  max="50",
     *  minMessage="Your full name must be at least {{ limit }} characters long.",
     *  maxMessage="Your full name cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That full name is not a valid {{ type }}.")
     */
    protected $name;

    /**
     * @ORM\Column(name="location", type="string", length=50)
     * @Assert\Length(
     *  min="1",
     *  max="50",
     *  minMessage="Your location must be at least {{ limit }} character long.",
     *  maxMessage="Your location cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That location is not a valid {{ type }}.")
     */
    protected $location;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @inheritDoc
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'       => $this->id,
            'name'     => $this->name,
            'location' => $this->location
        );
    }

    /**
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
