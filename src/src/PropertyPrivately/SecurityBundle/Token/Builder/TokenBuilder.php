<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Token\Builder;

use Doctrine\ORM\EntityRepository;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Entity\Token;
use PropertyPrivately\SecurityBundle\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Entity\Repository\ApplicationRepository;
use PropertyPrivately\SecurityBundle\Entity\Repository\TokenRepository;
use PropertyPrivately\SecurityBundle\Entity\Repository\UserRepository;
use PropertyPrivately\SecurityBundle\Validator\UserValidator;
use PropertyPrivately\SecurityBundle\Token\Utils\TokenGenerator;

/**
 * Token Builder
 */
class TokenBuilder
{
    /**
     * Constructor
     *
     * @param ApplicationRepository $appRepo
     * @param TokenRepository       $tokenRepo
     * @param UserRepository        $userRepo
     * @param UserValidator         $userValidator
     * @param TokenGenerator        $tokenGenerator
     */
    public function __construct(EntityRepository $appRepo,
        TokenRepository $tokenRepo, UserRepository $userRepo,
        UserValidator $userValidator, TokenGenerator $tokenGenerator)
    {
        $this->appRepo        = $appRepo;
        $this->tokenRepo      = $tokenRepo;
        $this->userRepo       = $userRepo;
        $this->userValidator  = $userValidator;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Build a token entity
     *
     * @param  string   $appSecret
     * @param  stdClass $credentials
     * @return Token
     */
    public function build($appSecret, $credentials)
    {
        if ( ! $appSecret || ! is_object($credentials)
            || empty($credentials->username) || empty($credentials->password)) {
            throw new MissingMandatoryParametersException();
        }

        $application = $this->appRepo->findOneByToken($appSecret);
        $user        = $this->userRepo->findOneByUsername($credentials->username);

        if ( ! $application || ! $user
            || ! $this->userValidator->validate($user, $credentials->username, $credentials->password)) {
            throw new BadCredentialsException('Bad credentials.');
        }

        $token = new Token();
        $token->setApplication($application);
        $token->setUser($user);
        $token->setToken($this->tokenGenerator->generate());

        return $token;
    }
}
