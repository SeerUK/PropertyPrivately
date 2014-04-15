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
 * Application Assembler
 */
class ApplicationResourceAssembler extends AbstractResourceAssembler
{
    /**
     * @see AbstractResourceAssembler::assemble()
     */
    public function assemble(array $nested = array())
    {
        $application = $this->getVariable('application');

        $this->rootResource->setVariables($application->toArray());
        $this->rootResource->addLinks($this->assembleLinks());

        if (in_array('user', $nested)) {
            $userAssembler = new UserResourceAssembler($this->router);
            $userAssembler->setVariable('user', $application->getUser());

            $this->rootResource->addResource('user', $userAssembler->assemble());
        }

        return $this->rootResource;
    }

    /**
     * Assemble links
     *
     * @return array
     */
    public function assembleLinks()
    {
        $application = $this->getVariable('application');
        $user        = $application->getUser();

        $links = array();
        $links['self'] = new Link($this->router->generate('pp_security_user_applications_get', ['id' => $application->getId()]));

        return $links;
    }
}
