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

/**
 * Token Assembler
 */
class TokenResourceAssembler extends AbstractResourceAssembler
{
    public function assemble()
    {
        $token = $this->getVariable('token');

        $this->rootResource->addLinks($this->assembleLinks());
        $this->rootResource->setVariables($token->toArray());

        return $this->rootResource;
    }

    public function assembleLinks()
    {
        $token = $this->getVariable('token');

        $links = array();
        $links['self'] = new Link($this->router->generate('pp_security_token_get', array(
            'id' => $token->getId()
        )));

        return $links;
    }
}
