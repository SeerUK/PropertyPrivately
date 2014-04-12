<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use PropertyPrivately\SecurityBundle\Entity\User;
use PropertyPrivately\SecurityBundle\Repository\UserRepository;

/**
 * API Key User Provider
 */
class ApiKeyUserProvider implements UserProviderInterface
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
     * Get username for API key
     *
     * @param  string $apiKey
     * @return string
     */
    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        // $username = ...;

        $username = 'Seer';

        return $username;
    }

    /**
     * Load the user from the database
     *
     * @param  string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepo->loadUserByUsername($username);
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
