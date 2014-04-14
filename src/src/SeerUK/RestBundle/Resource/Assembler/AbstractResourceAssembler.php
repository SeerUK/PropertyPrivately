<?php

/**
 * Seer UK REST Bundle
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
    protected $subAssemblers;

    /**
     * @var array
     */
    protected $variables;

    /**
     * Constructor
     *
     * @param Router   $router
     * @param Resource $rootResource
     */
    public function __construct(Router $router)
    {
        $this->router        = $router;
        $this->subAssemblers = array();
        $this->variables     = array();

        $this->setRootResource(new Resource());
    }

    /**
     * Assemble the root resource
     *
     * @param  array $nested
     * @return Resource
     */
    abstract public function assemble(array $nested = array());

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
     * @param  Resource $resource
     * @return AbstractResourceAssembler
     */
    public function setRootResource(Resource $resource)
    {
        $this->rootResource = $resource;

        return $this;
    }

    /**
     * Get assembler
     *
     * @param  string $name
     * @return AbstractResourceAssembler
     */
    public function getSubAssembler($name)
    {
        return $this->subAssemblers[$name];
    }

    /**
     * Get sub assemblers
     *
     * @return array
     */
    public function getSubAssemblers()
    {
        return $this->subAssemblers;
    }

    /**
     * Set sub assembler
     *
     * @param  string                    $name
     * @param  AbstractResourceAssembler $assembler
     * @return AbstractResourceAssembler
     */
    public function setSubAssembler($name, AbstractResourceAssembler $assembler)
    {
        $this->subAssemblers[$name] = $assembler;

        return $this;
    }

    /**
     * Set sub assemblers
     *
     * @param array $assemblers
     * @return AbstractResourceAssembler
     */
    public function setSubAssemblers(array $assemblers)
    {
        foreach ($assemblers as $name => $assembler) {
            $this->setSubAssembler($name, $assembler);
        }

        return $this;
    }

    /**
     * @see VariableModelInterface::getVariable()
     */
    public function getVariable($name)
    {
        return $this->variables[$name];
    }

    /**
     * @see VariableModelInterface::getVariables()
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @see VariableModelInterface::setVariable()
     */
    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @see VariableModelInterface::setVariables()
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @see VariableModelInterface::unsetVariable()
     */
    public function unsetVariable($name)
    {
        unset($this->variables[$name]);

        return $this;
    }

    /**
     * @see VariableModelInterface::clearVariables()
     */
    public function clearVariables()
    {
        $this->variables = array();

        return $this;
    }

    /**
     * @see VariableModelInterface::countVariables()
     */
    public function countVariables()
    {
        return count($this->variables);
    }
}
