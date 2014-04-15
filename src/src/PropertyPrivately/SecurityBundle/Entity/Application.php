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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * PropertyPrivately\SecurityBundle\Entity\Application
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\SecurityBundle\Repository\ApplicationRepository")
 * @ORM\Table(name="Application")
 */
class Application implements \Serializable, \JsonSerializable, ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"}, fetch="EAGER", inversedBy="applications")
     * @JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string", length=500)
     */
    protected $description;

    /**
     * @ORM\Column(name="token", type="string", length=64, unique=true)
     */
    protected $token;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @OneToMany(targetEntity="Token", mappedBy="application", cascade={"all"})
     */
    protected $tokens;

    /**
     * Constructor
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token       = $token;
        $this->description = '';
        $this->created     = new \DateTime();
        $this->enabled     = true;
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
     * Get user
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User $user
     * @return Application
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set description
     *
     * @param string $description
     * @return Application
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param  string $token
     * @return Application
     */
    public function setToken($token)
    {
        $this->token = $token;

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
     * @return Application
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get enabeld
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return (bool) $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param  boolean $enabled
     * @return Application
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * Is enabled?
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return (bool) $this->getEnabled();
    }

    /**
     * Get tokens
     *
     * @return Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'token'       => $this->token,
            'created'     => $this->created,
            'enabled'     => $this->enabled
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
            $this->name,
            $this->description,
            $this->token,
            $this->created,
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
            $this->name,
            $this->description,
            $this->token,
            $this->created,
            $this->enabled
        ) = unserialize($serialized);
    }
}
