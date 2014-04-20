<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity\Builder;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use PropertyPrivately\CoreBundle\Entity\Builder\AbstractEntityBuilder;
use PropertyPrivately\CoreBundle\Exception\MissingMandatoryParametersException;
use PropertyPrivately\SecurityBundle\Entity\Role;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * User Builder
 */
class UserBuilder extends AbstractEntityBuilder
{
    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * Constructor
     *
     * @param EncoderFactory $encoderFactory
     */
    public function __construct(EncoderFactory $encoderFactory)
    {
        parent::__construct();

        $this->encoder = $encoderFactory->getEncoder(new User());
    }

    /**
     * @see AbstractEntityBuilder::build()
     */
    public function build()
    {
        $credentials = $this->getVariable('credentials');

        if ( ! $this->isValidCredentials($credentials)) {
            throw new MissingMandatoryParametersException();
        }

        $user = new User();
        $user->setUsername($credentials->username);
        $user->setPassword($this->encoder->encodePassword($credentials->password, null));
        $user->setEmail($credentials->email);
        $user->setEnabled(true);

        return $user;
    }

    /**
     * Check if the credentials object given contains all of the required
     * fields to create a user
     *
     * @param  stdClass $credentials
     * @return boolean
     */
    private function isValidCredentials($credentials)
    {
        if (empty($credentials->username)
            || empty($credentials->password)
            || empty($credentials->email)) {
            return false;
        }

        return true;
    }
}
