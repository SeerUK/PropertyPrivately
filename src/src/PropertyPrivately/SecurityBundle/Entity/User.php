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

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\SecurityBundle\Entity\Person;
use PropertyPrivately\SecurityBundle\Entity\Role;


/**
 * PropertyPrivately\SecurityBundle\Entity\User
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\SecurityBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="PPSecurity.User")
 * @UniqueEntity(fields="username", message="That username is already taken.")
 * @UniqueEntity(fields="email", message="That email address is already in use.")
 */
class User implements AdvancedUserInterface, \Serializable, \JsonSerializable, ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="2",
     *  max="25",
     *  minMessage="Your username must be at least {{ limit }} characters long.",
     *  maxMessage="Your username cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That username is not a valid {{ type }}.")
     */
    protected $username;

    /**
     * @ORM\Column(name="password", type="string", length=128)
     */
    protected $password;

    /**
     * @Assert\Length(
     *  min="6",
     *  max="128",
     *  minMessage="Your password must be at least {{ limit }} characters long.",
     *  maxMessage="Your password cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\NotBlank()
     * @Assert\Type(type="string", message="That password is not a valid {{ type }}.")
     */
    protected $plainPassword;

    /**
     * @inheritDoc
     */
    protected $salt;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(message="That email address is not valid.")
     * @Assert\Length(
     *  min="3",
     *  max="255",
     *  minMessage="Your email must be at least {{ limit }} characters long.",
     *  maxMessage="Your email cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\NotBlank()
     */
    protected $email;

    /**
     * @OneToOne(targetEntity="Person", mappedBy="user", cascade={"persist"})
     */
    protected $person;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="PPSecurity.UserRoleMap",
     *     joinColumns={@JoinColumn(name="userId", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="roleId", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @OneToMany(targetEntity="Application", mappedBy="user")
     */
    protected $applications;

    /**
     * @OneToMany(targetEntity="PropertyPrivately\PropertyBundle\Entity\Property", mappedBy="user")
     */
    protected $properties;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean", message="Your enabled value is not a valid {{ type }}.")
     */
    protected $enabled;


    public function __construct()
    {
        $this->created = new \DateTime();
        $this->roles   = new ArrayCollection();
        $this->salt    = '';
        $this->enabled = true;
    }

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @inheritDoc
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->setPassword(null);

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
     * @inheritDoc
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @inheritDoc
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addRole(Role $role)
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Get applications
     *
     * @return array
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function equals(AdvancedUserInterface $user)
    {
        return $this->userName === $user->getUsername();
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'       => $this->id,
            'username' => $this->username,
            'email'    => $this->email,
            'created'  => $this->created->format(\DateTime::ISO8601),
            'enabled'  => $this->enabled
        );
    }

    /**
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->email,
            $this->enabled
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->email,
            $this->enabled
        ) = unserialize($serialized);
    }
}
