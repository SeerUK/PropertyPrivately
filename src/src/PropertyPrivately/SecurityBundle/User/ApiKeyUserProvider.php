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
use Doctrine\ORM\NoResultException;
use PropertyPrivately\SecurityBundle\Repository\TokenRepository;
use PropertyPrivately\SecurityBundle\User\UserProvider;

/**
 * API Key User Provider
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * Constructor
     *
     * @param UserRepository $userRepo
     */
    public function __construct(UserProvider $userProvider, TokenRepository $tokenRepository)
    {
        $this->userProvider    = $userProvider;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Get username for API key
     *
     * @param  string $apiKey
     * @return string
     */
    public function getUsernameForApiKey($apiKey)
    {
        try {
            $user = $this->tokenRepository->findOneByToken($apiKey)->getUser();
        } catch (NoResultException $e) {
            return false;
        }

        return $user->getUsername();
    }

    /**
     * Load the user from the database
     *
     * @param  string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        return $this->userProvider->loadUserByUsername($username);
    }

    /**
     * Must not be implemented.
     *
     * @param  UserInterface $user
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * Returns if the given class is supported by this provider
     *
     * @param  string $class
     * @return boolean
     */
    public function supportsClass($class)
    {
        return 'PropertyPrivately\SecurityBundle\Entity\User' === $class;
    }
}
