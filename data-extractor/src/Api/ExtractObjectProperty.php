<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

use Reliv\PipeRat2\Core\Api\ObjectToArray;
use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExtractObjectProperty
{
    const METHOD_PREFIX = 'get';
    const METHOD_BOOL_PREFIX = 'is';

    protected $objectToArray;

    /**
     * @param ObjectToArray $objectToArray
     */
    public function __construct(
        ObjectToArray $objectToArray
    ) {
        $this->objectToArray = $objectToArray;
    }

    /**
     * @param string $property
     * @param        $dataModel
     *
     * @return mixed
     * @throws InvalidValueType
     */
    public function __invoke(
        string $property,
        $dataModel
    ) {
        if (is_array($dataModel) && IsAssociativeArray::invoke($dataModel)) {
            return $dataModel[$property];
        }

        if (!is_object($dataModel)) {
            throw new InvalidValueType('Not object or associative array');
        }

        $methodBool = self::METHOD_BOOL_PREFIX . ucfirst($property);

        if (method_exists($dataModel, $methodBool)) {
            return $dataModel->$methodBool();
        }

        $method = self::METHOD_PREFIX . ucfirst($property);

        if (method_exists($dataModel, $method)) {
            return $dataModel->$method();
        }

        $publicProperties = $this->objectToArray->__invoke($dataModel);

        if (array_key_exists($property, $publicProperties)) {
            return $publicProperties[$property];
        }

        throw new InvalidValueType(
            'Could not get value for field: ' . $property
        );
    }
}
