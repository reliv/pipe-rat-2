<?php

namespace Reliv\PipeRat2\DataValueTypes\Service;

use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\DataExtractor\Api\IsAssociativeArray;
use Reliv\PipeRat2\DataExtractor\Api\IsJsonSerializableObject;
use Reliv\PipeRat2\DataExtractor\Api\IsTraversable;
use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;
use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValueTypesBasic implements ValueTypes
{
    protected $isTypeMap = [
        'string' => [
            ValueTypes::PRIMITIVE,
        ],
        'numeric' => [
            ValueTypes::PRIMITIVE,
        ],
        'null' => [
            ValueTypes::PRIMITIVE,
            ValueTypes::OBJECT,
        ],
        'bool' => [
            ValueTypes::PRIMITIVE,
        ],
        'object' => [
            ValueTypes::PRIMITIVE,
            ValueTypes::OBJECT,
            ValueTypes::COLLECTION,
        ],
        'array' => [
            ValueTypes::PRIMITIVE,
            ValueTypes::OBJECT,
            ValueTypes::COLLECTION,
        ],
    ];
    /**
     * @param mixed $dataModel
     * @param array $options
     *
     * @return string
     * @throws UnknownValueType
     */
    public function getType(
        $dataModel,
        array $options = []
    ): string {
        if (is_string($dataModel)) {
            return ValueTypes::PRIMITIVE;
        }

        if (is_numeric($dataModel)) {
            return ValueTypes::PRIMITIVE;
        }

        if (is_bool($dataModel)) {
            return ValueTypes::PRIMITIVE;
        }

        if ($dataModel === null) {
            return ValueTypes::PRIMITIVE;
        }

        if (IsJsonSerializableObject::invoke($dataModel)) {
            return ValueTypes::PRIMITIVE;
        }

        if (is_array($dataModel) && IsAssociativeArray::invoke($dataModel)) {
            return ValueTypes::OBJECT;
        }

        if (IsTraversable::invoke($dataModel)) {
            return ValueTypes::COLLECTION;
        }

        if (is_object($dataModel)) {
            return ValueTypes::OBJECT;
        }

        throw new UnknownValueType('Unknown type for: ' . Json::encode($dataModel));
    }

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param array  $options
     *
     * @return bool
     * @throws UnknownValueType
     */
    public function isType(
        $dataModel,
        string $type,
        array $options = []
    ): bool {
        $parsedType = $this->getType($dataModel);

        return ($parsedType === $type);
    }

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param array  $options
     *
     * @return void
     * @throws UnknownValueType|InvalidValueType
     */
    public function assertType(
        $dataModel,
        string $type,
        array $options = []
    ) {
        $parsedType = $this->getType($dataModel);

        if ($parsedType !== $type) {
            throw new InvalidValueType(
                'Value must be of type: ' . $type
                . ' got type: ' . $parsedType
            );
        }
    }
}
