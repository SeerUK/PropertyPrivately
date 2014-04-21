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
use PropertyPrivately\SecurityBundle\Entity\Person;

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

        if (in_array('person', $nested)) {
            $person = $user->getPerson();
            if ( ! $person) {
                $person = new Person();
            }

            $person = $person->toArray();

            $this->rootResource->setVariable('name', $person['name']);
            $this->rootResource->setVariable('location', $person['location']);
        }

        if (in_array('roles', $nested)) {
            $roles = array();
            foreach ($user->getRoles() as $role) {
                $roles[] = array(
                    'name' => $role->getName(),
                    'role' => $role->getRole()
                );
            }

            $this->rootResource->setVariable('roles', $roles);
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
        $links['self'] = new Link($this->router->generate('pp_security_users_get', ['username' => $user->getUsername()]));

        return $links;
    }
}
