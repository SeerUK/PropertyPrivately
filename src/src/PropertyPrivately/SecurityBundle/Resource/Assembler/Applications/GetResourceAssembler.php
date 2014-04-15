<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Resource\Assembler\Applications;

use SeerUK\RestBundle\Hal\Link\Link;
use SeerUK\RestBundle\Hal\Resource\Resource;
use SeerUK\RestBundle\Resource\Assembler\AbstractResourceAssembler;
use PropertyPrivately\SecurityBundle\Resource\Assembler\ApplicationResourceAssembler;

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
        $appAssembler = new ApplicationResourceAssembler($this->router);
        $appAssembler->setVariable('application', $this->getVariable('application'));
        $appAssembler->setRootResource($this->getRootResource());

        if ($this->hasVariable('user')) {
            $appAssembler->setVariable('user', $this->getVariable('user'));
        }

        return $appAssembler->assemble();
    }
}
