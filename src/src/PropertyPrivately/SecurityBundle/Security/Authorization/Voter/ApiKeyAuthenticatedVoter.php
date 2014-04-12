<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * API Key Authenticated Voter
 */
class ApiKeyAuthenticatedVoter implements VoterInterface
{
    const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';
    const IS_AUTHENTICATED_REMEMBERED = 'IS_AUTHENTICATED_REMEMBERED';
    const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * @var AuthenticationTrustResolverInterface
     */
    private $authenticationTrustResolver;

    /**
     * Constructor.
     *
     * @param AuthenticationTrustResolverInterface $authenticationTrustResolver
     */
    public function __construct(AuthenticationTrustResolverInterface $authenticationTrustResolver)
    {
        $this->authenticationTrustResolver = $authenticationTrustResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return null !== $attribute && (self::IS_AUTHENTICATED_FULLY === $attribute || self::IS_AUTHENTICATED_REMEMBERED === $attribute || self::IS_AUTHENTICATED_ANONYMOUSLY === $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;

            if (self::IS_AUTHENTICATED_FULLY === $attribute
                && $this->authenticationTrustResolver->isFullFledged($token)) {
                return VoterInterface::ACCESS_GRANTED;
            }

            if (self::IS_AUTHENTICATED_REMEMBERED === $attribute
                && $this->authenticationTrustResolver->isRememberMe($token)) {
                return VoterInterface::ACCESS_GRANTED;
            }

            if (self::IS_AUTHENTICATED_ANONYMOUSLY === $attribute
                && $this->authenticationTrustResolver->isAnonymous($token)) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}
