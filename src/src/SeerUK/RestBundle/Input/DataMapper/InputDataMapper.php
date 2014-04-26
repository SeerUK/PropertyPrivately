<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Input\DataMapper;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use SeerUK\RestBundle\Input\DataMapper\Exception\UnsupportedModelException;

/**
 * Input Data Mapper
 */
class InputDataMapper
{
    /**
     * @var Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Map data to model
     *
     * @param  array  $data
     * @param  object $model
     * @return object
     */
    public function mapDataToModel(array $data, $model)
    {
        foreach ($data as $property => $value) {
            try {
                $this->accessor->setValue($model, $property, $value);
            } catch (NoSuchPropertyException $e) {
                continue;
            }
        }

        return $model;
    }
}
