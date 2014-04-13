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
    public function getVariable($name);
    public function getVariables();
    public function setVariable($name, $value);
    public function setVariables(array $variables);
    public function unsetVariable($name);
    public function clearVariables();
    public function countVariables();
}
