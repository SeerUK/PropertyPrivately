<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\UserTokens;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
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

        $tokenAssembler = new TokenResourceAssembler($this->router);
        $tokenAssembler->setVariable('user', $this->getVariable('user'));

        foreach ($tokens as $token) {
            $tokenAssembler->setRootResource(new Resource());
            $tokenAssembler->setVariable('token', $token);

            $this->rootResource->addResource('tokens', $tokenAssembler->assemble(['application']), true);
        }

        return $this->rootResource;
    }
}
