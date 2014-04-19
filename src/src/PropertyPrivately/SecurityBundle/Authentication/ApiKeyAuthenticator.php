<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Authentication;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\User\ApiKeyUserProvider;

/**
 * API Key Authenticator
 */
class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var ApiKeyUserProvider
     */
    protected $userProvider;

    /**
     * Constructor
     *
     * @param ApiKeyUserProvider $userProvider
     */
    public function __construct(ApiKeyUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Create token to authenticate
     *
     * @param  Request $request
     * @param  string  $providerKey
     * @return PreAuthenticatedToken
     *
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        if ( ! $request->headers->has('X-API-App-Token') || empty($request->headers->get('X-API-App-Token'))) {
            throw new BadCredentialsException('No API app token found.');
        }

        if ( ! $request->headers->has('X-API-User-Token') || empty($request->headers->get('X-API-User-Token'))) {
            throw new BadCredentialsException('No API user token found.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            [
                'apiAppToken'  => $request->headers->get('X-API-App-Token'),
                'apiUserToken' => $request->headers->get('X-API-User-Token')
            ],
            $providerKey
        );
    }

    /**
     * Authenticate a token
     *
     * @param  TokenInterface        $token
     * @param  UserProviderInterface $userProvider
     * @param  string                $providerKey
     * @return PreAuthenticatedToken
     *
     * @throws AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $credentials  = $token->getCredentials();
        $apiUserToken = $credentials['apiUserToken'];
        $apiAppToken  = $credentials['apiAppToken'];


        $username = $this->userProvider->getUsernameForApiAppSecretAndApiKey($apiAppToken, $apiUserToken);

        if ( ! $username) {
            throw new AuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiUserToken)
            );
        }

        $user = $this->userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $apiUserToken,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * Supports which token?
     *
     * @param  TokenInterface $token
     * @param  string         $providerKey
     * @return boolean
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}
