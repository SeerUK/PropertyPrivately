<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Model;

/**
 * Variable Model Interface
 */
interface VariableModelInterface
{
    /**
     * Get variable
     *
     * @param  string $name
     * @return mixed
     */
    public function getVariable($name);

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables();

    /**
     * Set variable
     *
     * @param  string $name
     * @param  mixed  $value
     * @return VariableModelInterface
     */
    public function setVariable($name, $value);

    /**
     * Set variables
     *
     * @param  array $variables
     * @return VariableModelInterface
     */
    public function setVariables(array $variables);

    /**
     * Unset variable
     *
     * @param  string $name
     * @return VariableModelInterface
     */
    public function unsetVariable($name);

    /**
     * Clear variables
     *
     * @return VariableModelInterface
     */
    public function clearVariables();

    /**
     * Count variables
     *
     * @return integer
     */
    public function countVariables();
}
