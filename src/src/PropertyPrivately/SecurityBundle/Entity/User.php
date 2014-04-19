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

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;

/**
 * PropertyPrivately\SecurityBundle\Entity\User
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\SecurityBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="User")
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
     */
    protected $username;

    /**
     * @ORM\Column(name="password", type="string", length=128)
     */
    protected $password;

    /**
     * @inheritDoc
     */
    protected $salt;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="UserRoleMap",
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
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->salt  = '';
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
     * @inheritcDoc
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * @inheritcDoc
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

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
