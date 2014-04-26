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

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SeerUK\RestBundle\Input\DataMapper\InputDataMapper;

/**
 * Input Filter
 */
class InputFilter
{
    /**
     * @var array
     */
    private $definitions;

    /**
     * @var boolean
     */
    private $dispatched = false;

    /**
     * @var ConstraintViolationList
     */
    private $errors;

    /**
     * @var InputDataMapper
     */
    private $mapper;

    /**
     * @var array
     */
    private $models = array();

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var array
     */
    private $validationGroups;


    /**
     * Constructor
     *
     * @param ValidatorInterface       $validator
     * @param InputDictionaryInterface $dictionary
     * @param array                    $models
     */
    public function __construct(ValidatorInterface $validator, InputDictionaryInterface $dictionary, array $models)
    {
        $this->errors     = new ConstraintViolationList();
        $this->mapper     = new InputDataMapper();
        $this->validator  = $validator;

        $this->processDictionary($dictionary);
        $this->processModels($models);
    }

    /**
     * Dispatch data, map it to model
     *
     * @param  array   $data
     * @param  boolean $clearMissing
     */
    public function dispatch($data, $clearMissing = true)
    {
        if ($this->dispatched) {
            // Throw an error
            // You can't dispatch more than once!
        }

        $modelsData  = [];

        foreach ($this->definitions as $model => $definition) {
            if ( ! array_key_exists($model, $this->models)) {
                // Throw an error, the model in the definition was not given to use
                // in the models array
            }

            $modelsData[$model] = [];

            foreach ($definition as $property => $path) {
                if (is_int($property)) {
                    $property = $path;
                }

                if ( ! empty($data[$property])) {
                    $modelsData[$model][$path] = $data[$property];
                } else {
                    if ($clearMissing) {
                        $modelsData[$model][$path] = null;
                    }
                }
            }
        }

        foreach ($this->models as $key => $model) {
            if ( ! empty($modelsData[$key])) {
                $models[$key] = $this->mapper->mapDataToModel($modelsData[$key], $model);
            }

            $errors = $this->validator->validate(
                $this->models[$key],
                $this->validationGroups
            );

            $this->mapModelErrorsToDataProperty($model, $errors);
        }

        $this->dispatched = true;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData($model = null)
    {
        if (null !== $model) {
            if ( ! empty($this->models[$model])) {
                return $this->models[$model];
            } else {
                return null;
            }
        }

        return $this->models;
    }

    /**
     * Get errors
     *
     * @return ConstraintViolationList
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Did this data pass this filter's validation?
     *
     * @return boolean
     */
    public function isValid()
    {
        if ( ! $this->dispatched) {
            // Throw an error
            // Need to have dispatched to check validity
        }

        return count($this->errors) === 0;
    }

    /**
     * Map an error back to it's data property
     *
     * @param  object                  $model
     * @param  ConstraintViolationList $errors
     */
    private function mapModelErrorsToDataProperty($model, $errors)
    {
        if ( ! is_object($model)) {
            // Throw an error
            // We can only deal with objects, just like above.
        }

        $definition  = $this->definitions[get_class($model)];

        foreach ($errors as $error)
        {
            $this->errors->add(new ConstraintViolation(
                $error->getMessage(),
                $error->getMessageTemplate(),
                $error->getParameters(),
                $error->getRoot(),
                array_search($error->getPropertyPath(), $definition),
                $error->getInvalidValue(),
                $error->getPlural(),
                $error->getCode()
            ));
        }
    }

    private function processDictionary(InputDictionaryInterface $dictionary)
    {
        $definitions = $dictionary->getDefinitions();

        foreach ($definitions as $model => $definition) {
            foreach ($definition as $property => $path) {
                if (is_int($property)) {
                    $definition[$path] = $path;
                    unset($definition[$property]);
                }
            }

            $this->definitions[$model] = $definition;
        }

        $this->validationGroups = $dictionary->getValidationGroups();

        return $this;
    }

    /**
     * Process models
     *
     * @param  array  $models
     * @return InputFilter
     */
    private function processModels(array $models)
    {
        foreach ($models as $model) {
            if ( ! is_object($model)) {
                // Throw an error
                // Only objects can be models
            }

            $this->models[get_class($model)] = $model;
        }

        return $this;
    }
}
