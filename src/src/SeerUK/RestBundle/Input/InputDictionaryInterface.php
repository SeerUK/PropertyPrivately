<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Input;

/**
 * Input Dictionary Interface
 */
interface InputDictionaryInterface
{
    /**
     * Get dictionary definitions
     *
     * @return array
     */
    public function getDefinitions();

    /**
     * Get validation groups
     *
     * @return array
     */
    public function getValidationGroups();
}
