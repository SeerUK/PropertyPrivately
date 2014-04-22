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

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;

/**
 * DWI\SecurityBundle\Entity\Role
 *
 * @ORM\Entity
 * @ORM\Table(name="PPSecurity.Role")
 */
class Role implements RoleInterface, \JsonSerializable, ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    protected $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * @JoinTable(name="PPSecurity.UserRoleMap",
     *     joinColumns={@JoinColumn(name="roleId", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="userId", referencedColumnName="id")}
     * )
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function setRole($role)
    {
        $this->role = $role;

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
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'    => $this->id,
            'name'  => $this->name,
            'role'  => $this->role,
            'users' => $this->users
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
