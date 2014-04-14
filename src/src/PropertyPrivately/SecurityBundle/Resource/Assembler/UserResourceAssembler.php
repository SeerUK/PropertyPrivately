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
 * User Assembler
 */
class UserResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble()
    {
        $user = $this->getVariable('user');

        $this->rootResource->setVariables($user->toArray());
        $this->rootResource->addLinks($this->assembleLinks());

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    public function assembleLinks()
    {
        $user = $this->getVariable('user');

        $links = array();
        $links['self'] = new Link('/user/' . $user->getId());
        // $links['self'] = new Link($this->router->generate('pp_security_token_get', array(
        //     'id' => $token->getId()
        // )));

        return $links;
    }
}
