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
use PropertyPrivately\SecurityBundle\Repository\ApplicationRepository;
use PropertyPrivately\SecurityBundle\Repository\TokenRepository;
use PropertyPrivately\SecurityBundle\User\UserProvider;

/**
 * API Key User Provider
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var ApplicationRepository
     */
    private $appRepository;

    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * Constructor
     *
     * @param UserProvider          $userProvider
     * @param ApplicationRepository $appRepository
     * @param TokenRepository       $tokenRepository
     */
    public function __construct(UserProvider $userProvider, ApplicationRepository $appRepository,
        TokenRepository $tokenRepository)
    {
        $this->userProvider    = $userProvider;
        $this->appRepository   = $appRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Get username for API app secret and API key
     *
     * @param  string $apiAppSecret
     * @param  string $apiKey
     * @return string
     */
    public function getUsernameForApiAppSecretAndApiKey($apiAppSecret, $apiKey)
    {
        if ( ! $application = $this->appRepository->findOneByToken($apiAppSecret)) {
            return false;
        }

        $token = $this->tokenRepository->findOneBy([
            'application' => $application->getId(),
            'token'       => $apiKey
        ]);

        if ( ! $token) {
            return false;
        }

        return $token->getUser()->getUsername();
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
