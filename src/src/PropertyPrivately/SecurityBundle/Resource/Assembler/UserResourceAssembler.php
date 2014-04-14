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
use PropertyPrivately\SecurityBundle\Resource\Assembler\RoleResourceAssembler;

/**
 * User Assembler
 */
class UserResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $user = $this->getVariable('user');

        $this->rootResource->addLinks($this->assembleLinks());
        $this->rootResource->setVariables($user->toArray());
        $this->rootResource->unsetVariable('roles');

        if (in_array('roles', $nested)) {
            $roles         = $user->getRoles();
            $roleAssembler = new RoleResourceAssembler($this->router);

            foreach ($roles as $role) {
                $roleAssembler->setRootResource(new Resource());
                $roleAssembler->setVariable('role', $role);

                $this->rootResource->addResource('roles', $roleAssembler->assemble(), true);
            }
        }

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    private function assembleLinks()
    {
        $user = $this->getVariable('user');

        $links = array();
        $links['self'] = new Link($this->router->generate('pp_security_users_get', ['id' => $user->getId()]));

        return $links;
    }
}
