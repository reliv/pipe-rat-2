<?php

namespace Reliv\PipeRat2\DataValueTypes\Service;

use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\DataExtractor\Api\IsAssociativeArray;
use Reliv\PipeRat2\DataExtractor\Api\IsTraversable;
use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;
use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValueTypesBasic implements ValueTypes
{
    /**
     * @param mixed  $dataModel
     * @param string $context
     *
     * @return string
     * @throws UnknownValueType
     */
    public function getActualType(
        $dataModel,
        string $context = 'undefined'
    ): string {
        if (is_string($dataModel)) {
            return ValueTypes::ACTUAL_STRING;
        }

        if (is_numeric($dataModel)) {
            return ValueTypes::ACTUAL_NUMERIC;
        }

        if (is_bool($dataModel)) {
            return ValueTypes::ACTUAL_BOOL;
        }

        if ($dataModel === null) {
            return ValueTypes::ACTUAL_NULL;
        }

        if (is_array($dataModel)) {
            return ValueTypes::ACTUAL_ARRAY;
        }

        if (is_object($dataModel)) {
            return ValueTypes::ACTUAL_OBJECT;
        }

        throw new UnknownValueType(
            'Unknown actual type for: ' . Json::encode($dataModel)
            . ' in context: (' . $context . ')'
        );
    }

    /**
     * @param mixed  $dataModel
     * @param string $context
     *
     * @return string
     * @throws UnknownValueType
     */
    public function getType(
        $dataModel,
        string $context = 'undefined'
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

        if (is_array($dataModel) && IsAssociativeArray::invoke($dataModel)) {
            return ValueTypes::OBJECT;
        }

        if (IsTraversable::invoke($dataModel)) {
            return ValueTypes::COLLECTION;
        }

        if (is_object($dataModel)) {
            return ValueTypes::OBJECT;
        }

        throw new UnknownValueType(
            'Unknown type for: ' . Json::encode($dataModel)
            . ' in context: (' . $context . ')'
        );
    }

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param string $context
     *
     * @return bool
     * @throws UnknownValueType
     */
    public function isType(
        $dataModel,
        string $type,
        string $context = 'undefined'
    ): bool {
        $actualType = $this->getActualType($dataModel);

        if (!array_key_exists($actualType, self::TYPE_MAP)) {
            throw new UnknownValueType(
                'Unknown type for parse type: (' . $actualType . ')'
                . ' in context: (' . $context . ')'
                . ' with data: ' . Json::encode($dataModel)
            );
        }

        return in_array($type, self::TYPE_MAP[$actualType]);
    }

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param string $context
     *
     * @return void
     * @throws UnknownValueType|InvalidValueType
     */
    public function assertType(
        $dataModel,
        string $type,
        string $context = 'undefined'
    ) {
        if (!$this->isType($dataModel, $type)) {
            $parsedType = $this->getType($dataModel);
            throw new InvalidValueType(
                'Value must be of type: (' . $type . ')'
                . ' got type: (' . $parsedType . ')'
                . ' in context: (' . $context . ')'
                . ' with data: ' . Json::encode($dataModel)
            );
        }
    }
}
