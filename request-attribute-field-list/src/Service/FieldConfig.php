<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Service;

use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FieldConfig
{
    const PRIMITIVE = ValueTypes::PRIMITIVE;
    const OBJECT = ValueTypes::OBJECT;
    /** @deprecated Use more specific collection */
    const COLLECTION = ValueTypes::COLLECTION;
    const PRIMITIVE_COLLECTION = ValueTypes::PRIMITIVE_COLLECTION;
    const OBJECT_COLLECTION = ValueTypes::OBJECT_COLLECTION;
    const COLLECTION_COLLECTION = ValueTypes::COLLECTION_COLLECTION;

    const KEY_TYPE = '_type';
    const KEY_PROPERTIES = '_properties';
    const KEY_INCLUDE = '_include';

    /**
     * @param string $type
     *
     * @return void
     * @throws UnknownFieldType
     */
    public function assertValidType($type);

    /**
     * @param array  $fieldConfig
     * @param string $type
     *
     * @return void
     * @throws InvalidFieldType
     * @throws UnknownFieldType
     */
    public function assertIsType(array $fieldConfig, string $type);

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function hasType(array $fieldConfig): bool;

    /**
     * @param array  $fieldConfig
     * @param string $default
     *
     * @return string
     * @throws UnknownFieldType
     */
    public function getType(
        array $fieldConfig,
        $default = ValueTypes::PRIMITIVE
    ): string;

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function hasProperties(array $fieldConfig): bool;

    /**
     * @param array $fieldConfig
     * @param null  $default
     *
     * @return array|mixed|null
     */
    public function getProperties(
        array $fieldConfig,
        $default = null
    );

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function canInclude(
        array $fieldConfig
    ): bool;

    /**
     * @param string $type
     * @param array  $properties
     * @param bool   $include
     * @param array  $otherConfigs
     *
     * @return array
     * @throws UnknownFieldType
     */
    public function buildFieldConfig(
        string $type,
        array $properties = [],
        bool $include = false,
        array $otherConfigs = []
    ): array;
}
