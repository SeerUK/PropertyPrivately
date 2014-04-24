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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * PropertyPrivately\PropertyBundle\Entity\Property
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\PropertyBundle\Entity\Repository\PropertyRepository")
 * @ORM\Table(name="PPProperty.Property")
 * @ORM\HasLifecycleCallbacks
 */
class Property implements ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="10",
     *  max="50",
     *  minMessage="Your title must be at least {{ limit }} characters long.",
     *  maxMessage="Your title cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That title is not a valid {{ type }}.")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="50",
     *  max="1000",
     *  minMessage="Your description must be at least {{ limit }} characters long.",
     *  maxMessage="Your description cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That description is not a valid {{ type }}.")
     */
    protected $description;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ManyToOne(targetEntity="PropertyPrivately\SecurityBundle\Entity\User", fetch="EAGER", inversedBy="properties")
     * @JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @OneToMany(targetEntity="Image", mappedBy="property")
     */
    protected $images;


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
     * Set title
     *
     * @param string $title
     * @return Property
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Property
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
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param  \DateTime $created
     * @return Token
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Property
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get images
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @ORM\PrePersist
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
        return array(
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'created'     => $this->created->format(\DateTime::ISO8601)
        );
    }
}
