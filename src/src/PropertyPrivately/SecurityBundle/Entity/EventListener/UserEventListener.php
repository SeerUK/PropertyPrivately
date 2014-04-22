<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * User Event Listener
 */
class UserEventListener
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * Constructor
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Get encoder for given user
     *
     * @param  AdvancedUserInterface $user
     * @return Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    public function getEncoder(AdvancedUserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * @inheritDoc
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $user = $event->getEntity();

        if ( ! $user instanceof User) {
            return;
        }

        $this->updateUser($user);
        $event->setNewValue('password', $user->getPassword());
    }

    /**
     * @inheritDoc
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $user = $event->getEntity();

        if ( ! $user instanceof User) {
            return;
        }

        $this->updateUser($user);
    }

    /**
     * Update a User entity
     *
     * @param User $user
     */
    private function updateUser(User $user)
    {
        $plainPassword = $user->getPlainPassword();

        if ( ! empty($plainPassword)) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
            $user->eraseCredentials();
        }
    }
}
