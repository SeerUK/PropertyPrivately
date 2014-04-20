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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use SeerUK\RestBundle\Entity\Patcher\PatchableEntityInterface;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;

/**
 * PropertyPrivately\SecurityBundle\Entity\Token
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\SecurityBundle\Entity\Repository\TokenRepository")
 * @ORM\Table(name="Token")
 */
class Token implements \Serializable, \JsonSerializable, ArrayableInterface, PatchableEntityInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Application", fetch="EAGER", inversedBy="tokens")
     * @JoinColumn(name="applicationId", referencedColumnName="id")
     * @Assert\NotNull()
     */
    protected $application;

    /**
     * @ManyToOne(targetEntity="User", fetch="EAGER")
     * @JoinColumn(name="userId", referencedColumnName="id")
     * @Assert\NotNull()
     */
    protected $user;

    /**
     * @ORM\Column(name="description", type="string", length=50)
     * @Assert\Length(
     *      min = "0",
     *      max = "50",
     *      minMessage = "Your description must be at least {{ limit }} characters.",
     *      maxMessage = "Your description cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Type(type="string", message="Your description value is not a valid {{ type }}.")
     */
    protected $description;

    /**
     * @ORM\Column(name="token", type="string", length=64, unique=true)
     * @Assert\NotBlank()
     */
    protected $token;

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

    /**
     * Constructor
     *
     * @param string $token
     */
    public function __construct()
    {
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
     * Get application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set application
     *
     * @param  Application $application
     * @return Token
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

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
     * Set user
     *
     * @param  User $user
     * @return Token
     */
    public function setUser(User $user)
    {
        $this->user = $user;

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
     * @return Token
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
     * @return Token
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
     * @param boolean $enabled
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
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'          => $this->id,
            'description' => $this->description,
            'token'       => $this->token,
            'created'     => $this->created->format(\DateTime::ISO8601),
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
     * @see PatchableEntityInterface::getPatchableProperties()
     */
    public function getPatchableProperties()
    {
        return array(
            'description',
            'enabled'
        );
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
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
            $this->description,
            $this->token,
            $this->created,
            $this->enabled
        ) = unserialize($serialized);
    }
}
