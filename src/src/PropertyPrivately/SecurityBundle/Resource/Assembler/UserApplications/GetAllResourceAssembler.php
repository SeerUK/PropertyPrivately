<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\UserApplications;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\ApplicationResourceAssembler;

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
        $applications = $this->getVariable('applications');
        $this->rootResource->setVariable('total', count($applications));

        $appAssembler = new ApplicationResourceAssembler($this->router);
        $appAssembler->setVariable('user', $this->getVariable('user'));

        foreach ($applications as $application) {
            $appAssembler->setRootResource(new Resource());
            $appAssembler->setVariable('application', $application);

            $this->rootResource->addResource('applications', $appAssembler->assemble(), true);
        }

        return $this->rootResource;
    }
}
