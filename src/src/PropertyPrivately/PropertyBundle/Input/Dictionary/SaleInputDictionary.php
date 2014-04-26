<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Input\Dictionary;

use SeerUK\RestBundle\Input\InputDictionaryInterface;
use PropertyPrivately\PropertyBundle\Entity\Sale;

/**
 * Sale Input Dictionary
 */
class SaleInputDictionary implements InputDictionaryInterface
{
    /**
     * @var boolean
     */
    private $isUpdate;

    /**
     * Constructor
     *
     * @param boolean $isUpdate
     */
    public function __construct($isUpdate = false)
    {
        $this->isUpdate = (bool) $isUpdate;
    }

    /**
     * @see InputDictionaryInterface::getDefinitions()
     */
    public function getDefinitions()
    {
        return [
            Sale::class => [
                'price',
                'enabled'
            ]
        ];
    }

    /**
     * @see InputDictionaryInterface::getValidationGroups()
     */
    public function getValidationGroups()
    {
        $validationGroups = ['Default'];

        if ( ! $this->isUpdate) {
            $validationGroups[] = 'CREATE';
        } else {
            $validationGroups[] = 'UPDATE';
        }

        return $validationGroups;
    }
}
