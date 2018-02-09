<?php

namespace Reliv\PipeRat2\DataValueTypes\Service;

use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;
use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ValueTypes
{
    /** TYPES */
    /*
     * (json-encode-able) int, string, bool, null, array, basic object
     */
    const PRIMITIVE = 'primitive';

    /**
     * object, associative array, null
     */
    const OBJECT = 'object';

    /**
     * @deprecated Use more specific collection
     * array, traversable of unknown values
     */
    const COLLECTION = 'collection';

    /**
     * array, traversable of primitives
     */
    const PRIMITIVE_COLLECTION = 'primitive-collection';

    /**
     * array, traversable of objects
     */
    const OBJECT_COLLECTION = 'object-collection';

    /**
     * array, traversable of collection
     */
    const COLLECTION_COLLECTION = 'collection-collection';

    /** VALUE TYPES */
    const ACTUAL_STRING = 'string';
    const ACTUAL_NUMERIC = 'numeric';
    const ACTUAL_NULL = 'null';
    const ACTUAL_BOOL = 'bool';
    const ACTUAL_OBJECT = 'object';
    const ACTUAL_ARRAY = 'array';

    /**
     * [
     *  '{value-type' => ['{default-type}', '{other-type}']
     * ]
     */
    const TYPE_MAP
        = [
            self::ACTUAL_STRING => [
                ValueTypes::PRIMITIVE,
            ],
            self::ACTUAL_NUMERIC => [
                ValueTypes::PRIMITIVE,
            ],
            self::ACTUAL_NULL => [
                ValueTypes::PRIMITIVE,
                ValueTypes::OBJECT,
            ],
            self::ACTUAL_BOOL => [
                ValueTypes::PRIMITIVE,
            ],
            self::ACTUAL_OBJECT => [
                ValueTypes::OBJECT,
                ValueTypes::COLLECTION,
                ValueTypes::PRIMITIVE_COLLECTION,
                ValueTypes::OBJECT_COLLECTION,
                ValueTypes::COLLECTION_COLLECTION,
            ],
            self::ACTUAL_ARRAY => [
                ValueTypes::COLLECTION,
                ValueTypes::PRIMITIVE_COLLECTION,
                ValueTypes::OBJECT_COLLECTION,
                ValueTypes::COLLECTION_COLLECTION,
                ValueTypes::OBJECT,
            ],
        ];

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
    ): string;

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
    ): string;

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
    ): bool;

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
    );
}
