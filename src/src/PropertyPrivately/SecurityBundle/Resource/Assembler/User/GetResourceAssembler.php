<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\User;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\UserResourceAssembler;

/**
 * Get Action Assembler
 */
class GetResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $this->setVariable('links', $this->rootResource->getLinks());

        $userAssembler = new UserResourceAssembler($this->router);
        $userAssembler->setVariable('user', $this->getVariable('user'));
        $userAssembler->setRootResource($this->getRootResource());

        $resource =  $userAssembler->assemble(['roles']);
        $resource->removeLink('self');
        $resource->addLinks($this->assembleLinks());

        return $resource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    private function assembleLinks()
    {
        $links = $this->getVariable('links');
        $links['user:applications'] = new Link($this->router->generate('pp_security_user_applications_get_all'));
        $links['user:properties']   = new Link('/NYI');
        $links['user:tokens']       = new Link($this->router->generate('pp_security_user_tokens_get_all'));

        return $links;
    }
}
