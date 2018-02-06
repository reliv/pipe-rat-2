<?php

namespace Reliv\PipeRat2\DataFieldList\Service;

use Reliv\PipeRat2\DataFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\DataFieldList\Exception\UnknownFieldType;
use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FieldConfigBasic implements FieldConfig
{
    protected $validTypes
        = [
            ValueTypes::PRIMITIVE,
            ValueTypes::OBJECT,
            ValueTypes::COLLECTION,
        ];

    protected $example
        = [
            '_type' => 'object',
            '_properties' => [
                'name' => [
                    // (json-encode-able) int, string, bool, null, array, basic object
                    '_type' => 'primitive'
                ],
                'date' => [
                    // object, null
                    '_type' => 'object',
                    // Will this be included be default
                    '_include' => true,
                    '_properties' => [],
                ],
                'skus' => [
                    '_type' => 'collection', // array, traversable
                    // If it has properties, it but be a collection of objects, else is collection of primitives
                    '_properties' => [
                        'number' => [
                            '_type' => 'primitive'
                        ],
                        'properties' => [
                            '_type' => 'collection',
                            '_properties' => [
                                'type' => [
                                    '_type' => 'primitive'
                                ]
                            ],
                        ]
                    ],
                ],
                'tags' => [
                    '_type' => 'collection', // array, traversable
                    // no properties = primitive
                ],
            ],
        ];

    /**
     * @param string $type
     *
     * @return void
     * @throws UnknownFieldType
     */
    public function assertValidType($type)
    {
        if (!array_key_exists($type, $this->validTypes)) {
            throw new UnknownFieldType(
                'Unknown field type: ' . json_encode($type)
            );
        }
    }

    /**
     * @param array  $fieldConfig
     * @param string $type
     *
     * @return void
     * @throws InvalidFieldType
     * @throws UnknownFieldType
     */
    public function assertIsType(array $fieldConfig, string $type)
    {
        $actualType = $this->getType($fieldConfig);
        if ($actualType !== $type) {
            throw new InvalidFieldType(
                'Expected type: ' . $type . ' got: ' . $actualType
            );
        }
    }

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function hasType(array $fieldConfig): bool
    {
        return array_key_exists(self::KEY_TYPE, $fieldConfig);
    }

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
    ): string {
        if (!array_key_exists(self::KEY_TYPE, $fieldConfig)) {
            return $default;
        }

        static::assertValidType($fieldConfig[self::KEY_TYPE]);

        return $fieldConfig[self::KEY_TYPE];
    }

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function hasProperties(array $fieldConfig): bool
    {
        return array_key_exists(self::KEY_PROPERTIES, $fieldConfig);
    }

    /**
     * @param array $fieldConfig
     * @param null  $default
     *
     * @return array|mixed|null
     */
    public function getProperties(
        array $fieldConfig,
        $default = null
    ) {
        if (!array_key_exists(self::KEY_PROPERTIES, $fieldConfig)) {
            return $default;
        }

        return $fieldConfig[self::KEY_PROPERTIES];
    }

    /**
     * @param array $fieldConfig
     *
     * @return bool
     */
    public function canInclude(
        array $fieldConfig
    ): bool {
        if (!array_key_exists(self::KEY_INCLUDE, $fieldConfig)) {
            return false;
        }

        return (bool)$fieldConfig[self::KEY_INCLUDE];
    }

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
    ): array {
        if ($type === ValueTypes::PRIMITIVE) {
            return [
                self::KEY_TYPE => ValueTypes::PRIMITIVE,
                self::KEY_INCLUDE => $include,
            ];
        }

        if ($type === ValueTypes::OBJECT) {
            return [
                self::KEY_TYPE => ValueTypes::OBJECT,
                self::KEY_INCLUDE => $include,
                self::KEY_PROPERTIES => $properties,
            ];
        }

        if ($type === ValueTypes::COLLECTION) {
            return [
                self::KEY_TYPE => ValueTypes::COLLECTION,
                self::KEY_INCLUDE => $include,
                self::KEY_PROPERTIES => $properties,
            ];
        }

        static::assertValidType($type);
    }
}
