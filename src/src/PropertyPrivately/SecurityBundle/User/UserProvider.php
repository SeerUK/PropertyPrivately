<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\ORM\NoResultException;
use PropertyPrivately\SecurityBundle\Entity\User;
use PropertyPrivately\SecurityBundle\Entity\Repository\UserRepository;

/**
 * User Provider
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * Constructor
     *
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Load a user by username
     *
     * @param  string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->userRepo->findOneByUsername($username);
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf(
                'Unable to find an active user PropertyPrivatelySecurityBundle:User object identified by "%s".',
                $username
            ), 0, $e);
        }

        return $user;
    }

    /**
     * Refresh user
     *
     * @param  UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->userRepo->findOneById($user->getId());
    }

    /**
     * Check if this user provider supports the given class
     *
     * @param  string $class
     * @return boolean
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * Get entity name
     *
     * @return string
     */
    private function getEntityName()
    {
        return 'PropertyPrivately\SecurityBundle\Entity\User';
    }
}
