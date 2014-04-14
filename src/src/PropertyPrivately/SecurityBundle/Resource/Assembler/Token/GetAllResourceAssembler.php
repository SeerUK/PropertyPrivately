<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\Token;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;

/**
 * Get All Action Assembler
 */
class GetAllResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble()
    {
        $tokens = $this->getVariable('tokens');
        $this->rootResource->setVariable('total', count($tokens));

        $tokenAssembler = $this->getSubAssembler('token');
        $tokenAssembler->setVariable('user', $this->getVariable('user'));

        foreach ($tokens as $token) {
            // Resfresh the root resource each time
            $tokenAssembler->setRootResource(new Resource());
            $tokenAssembler->setVariable('token', $token);

            $this->rootResource->addResource('tokens', $tokenAssembler->assemble(), true);
        }

        return $this->rootResource;
    }
}
