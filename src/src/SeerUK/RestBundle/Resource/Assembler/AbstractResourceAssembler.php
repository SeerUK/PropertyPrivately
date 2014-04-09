<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Resource\Assembler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use SeerUK\RestBundle\Model\VariableModelInterface;
use SeerUK\RestBundle\Hal\Resource\Resource;

/**
 * Abstract Resource Assembler
 */
abstract class AbstractResourceAssembler implements VariableModelInterface
{
    /**
     * @var Resource
     */
    protected $rootResource;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $variables;

    /**
     * Constructor
     *
     * @param Resource $rootResource
     */
    public function __construct(Resource $rootResource, Router $router)
    {
        $this->router    = $router;
        $this->variables = array();

        $this->setRootResource($rootResource);
    }

    /**
     * Assemble the root resource
     *
     * @return Resource
     */
    abstract public function assemble();

    /**
     * Get root resource
     *
     * @return Resource
     */
    public function getRootResource()
    {
        return $this->rootResource;
    }

    /**
     * Set root resource
     *
     * @param Resource $resource
     */
    public function setRootResource(Resource $resource)
    {
        $this->rootResource = $resource;

        return $this;
    }

    /**
     * Get a variable
     *
     * @param  string $name
     * @return mixed
     */
    public function getVariable($name)
    {
        return $this->variables[$name];
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
