<?php

namespace Reliv\PipeRat2\DataFieldList\Service;

use Reliv\PipeRat2\DataFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\DataFieldList\Exception\UnknownFieldType;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FieldConfig
{
    const PRIMITIVE = ValueTypes::PRIMITIVE;
    const OBJECT = ValueTypes::OBJECT;
    const COLLECTION = ValueTypes::COLLECTION;

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
     *
     * @return array
     * @throws UnknownFieldType
     */
    public function buildFieldConfig(
        string $type,
        array $properties = [],
        bool $include = false
    ): array;
}
