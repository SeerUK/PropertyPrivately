<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\UserTokensApplication;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\ApplicationResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\TokenResourceAssembler;

/**
 * Get All Action Assembler
 */
class GetAllResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $tokens = $this->getVariable('tokens');
        $this->rootResource->setVariable('total', count($tokens));
        $this->rootResource->addLinks($this->assembleLinks());
        $this->rootResource->addResource('application', $this->assembleApplication());

        // Create tokens
        $tokenAssembler = new TokenResourceAssembler($this->router);

        foreach ($tokens as $token) {
            $tokenAssembler->setRootResource(new Resource());
            $tokenAssembler->setVariable('token', $token);

            $this->rootResource->addResource('tokens', $tokenAssembler->assemble(), true);
        }

        return $this->rootResource;
    }

    /**
     * Assemble application resource
     *
     * @return Resource
     */
    private function assembleApplication()
    {
        $appAssembler = new ApplicationResourceAssembler($this->router);
        $appAssembler->setVariable('application', $this->getVariable('application'));

        $application = $appAssembler->assemble();
        $application->unsetVariable('token');

        return $application;
    }

    private function assembleLinks()
    {
        $links = array();
        $links['user'] = new Link($this->router->generate('pp_security_user_get'));

        return $links;
    }
}
