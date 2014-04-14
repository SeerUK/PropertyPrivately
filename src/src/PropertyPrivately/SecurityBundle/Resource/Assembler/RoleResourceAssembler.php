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
use PropertyPrivately\SecurityBundle\Resource\Assembler\UserResourceAssembler;

/**
 * Role Assembler
 */
class RoleResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $role = $this->getVariable('role');

        $this->rootResource->addLinks($this->assembleLinks());
        $this->rootResource->setVariables($role->toArray());
        $this->rootResource->unsetVariable('users');

        if (in_array('users', $nested)) {
            $users         = $role->getUsers();
            $userAssembler = new UserResourceAssembler($this->router);

            foreach ($users as $user) {
                $userAssembler->setVariable('user', $user);

                $this->rootResource->addResource('users', $userAssembler->assemble(), true);
            }
        }

        return $this->rootResource;
    }

    private function assembleLinks()
    {
        $role = $this->getVariable('role');

        $links = array();
        $links['self'] = new Link('/roles/' . $role->getId());

        return $links;
    }
}
