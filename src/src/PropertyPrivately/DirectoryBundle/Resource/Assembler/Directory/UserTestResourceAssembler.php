<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\DirectoryBundle\Resource\Assembler\Directory;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;

/**
 * User Test Resource Assembler
 */
class UserTestResourceAssembler extends AbstractResourceAssembler
{
    /**
     * Assemble the user test resource
     *
     * @return Resource
     */
    public function assemble(array $nested = array())
    {
        $this->rootResource->addLinks($this->assembleLinks());

        $users = $this->assembleUserResources();
        foreach ($users as $user) {
            $this->rootResource->addResource('users', $user, true);
        }

        $this->rootResource->setVariable('embeddedUsers', count($this->rootResource->getResource('users')));
        $this->rootResource->setVariable('embeddedResources', $this->rootResource->countResources());

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    public function assembleLinks()
    {
        $links = array();
        $links['first'] = new Link($this->router->generate('property_privately_directory_user_test'));
        $links['last']  = new Link($this->router->generate('property_privately_directory_user_test'));

        return $links;
    }

    /**
     * Assemble User Resources
     *
     * @return array
     */
    public function assembleUserResources()
    {
        $users = array();

        $users[1] = new Resource(array(
            'id' => 1,
            'name' => 'Seer',
            'roles' => array('ROLE_USER', 'ROLE_ADMIN'),
        ));
        $users[1]->addLink('self', new Link('/app_dev.php/users/1'));

        $users[2] = new Resource(array(
            'id' => 2,
            'name' => 'Test',
            'roles' => array('ROLE_USER'),
        ));
        $users[2]->addLink('self', new Link('/app_dev.php/users/2'));

        return $users;
    }
}
