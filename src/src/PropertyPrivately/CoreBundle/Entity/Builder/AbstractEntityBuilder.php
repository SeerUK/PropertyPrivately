<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Entity\Builder;

use SeerUK\RestBundle\Model\VariableModelInterface;

/**
 * Builder Interface
 */
abstract class AbstractEntityBuilder implements VariableModelInterface
{
    /**
     * @var array
     */
    private $variables;

    /**
     * Constructor
     *
     * @param array $variables
     */
    public function __construct(array $variables = array())
    {
        $this->variables = $variables;
    }

    /**
     * Build an entity
     *
     * @return mixed
     */
    abstract public function build();

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
     * @see VariableModelInterface::hasVariable()
     */
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
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
