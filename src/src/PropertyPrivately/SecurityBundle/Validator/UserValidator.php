<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Validator;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * User Validator
 */
class UserValidator
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * Constructor
     *
     * @param EncoderFactoryInterface $encodeFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Validate user credentials
     *
     * @param  User   $user
     * @param  string $username
     * @param  string $password
     * @return boolean
     */
    public function validate(User $user, $username, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        return ($user->getUsername() === $username
            && $encoder->isPasswordValid($user->getPassword(), $password, null));
    }
}
