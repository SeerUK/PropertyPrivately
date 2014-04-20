<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\ApplicationResourceAssembler;

/**
 * Token Assembler
 */
class TokenResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $token = $this->getVariable('token');

        $this->rootResource->setVariables($token->toArray());
        $this->rootResource->addLinks($this->assembleLinks());

        if (in_array('application', $nested)) {
            $appAssembler = new ApplicationResourceAssembler($this->router);
            $appAssembler->setVariable('application', $token->getApplication());

            $application = $appAssembler->assemble();
            $application->unsetVariable('token');

            $this->rootResource->addResource('application', $application);
        }

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    public function assembleLinks()
    {
        $token       = $this->getVariable('token');
        $application = $token->getApplication();
        $user        = $token->getUser();

        $links = array();
        $links['self']              = new Link($this->router->generate('pp_security_user_tokens_get', ['id' => $token->getId()]));
        $links['token:application'] = new Link($this->router->generate('pp_security_applications_get', ['id' => $application->getId()]));
        $links['token:user']        = new Link($this->router->generate('pp_security_users_get', ['username' => $user->getUsername()]));

        return $links;
    }
}
