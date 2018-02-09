<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Service;

use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;

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
            ValueTypes::PRIMITIVE_COLLECTION,
            ValueTypes::OBJECT_COLLECTION,
            ValueTypes::COLLECTION_COLLECTION,
        ];

    protected $example
        = [
            self::KEY_TYPE => 'object',
            self::KEY_PROPERTIES => [
                'name' => [
                    // (json-encode-able) int, string, bool, null, array, basic object
                    self::KEY_TYPE => self::PRIMITIVE
                ],
                'date' => [
                    // object, null
                    self::KEY_TYPE => self::OBJECT,
                    self::KEY_PROPERTIES => [
                        'date' => [
                            self::KEY_TYPE => self::PRIMITIVE
                        ],
                    ],
                    // Will this be included be default
                    '_include' => true,
                ],
                'skus' => [
                    '_type' => self::OBJECT_COLLECTION, // array, traversable
                    // If it has properties, it but be a collection of objects, else is collection of primitives
                    '_properties' => [
                        'number' => [
                            self::KEY_TYPE => self::PRIMITIVE
                        ],
                        'properties' => [
                            self::KEY_TYPE => self::OBJECT_COLLECTION,
                            self::KEY_PROPERTIES => [
                                'type' => [
                                    self::KEY_TYPE => 'primitive'
                                ]
                            ],
                        ]
                    ],
                ],
                'tags' => [
                    // array, traversable
                    self::KEY_TYPE => self::PRIMITIVE_COLLECTION,
                    // no properties = primitive
                ],
                'nest' => [
                    // array, traversable
                    self::KEY_TYPE => self::COLLECTION_COLLECTION,
                    self::KEY_PROPERTIES => [
                        self::KEY_TYPE => self::OBJECT_COLLECTION,
                        self::KEY_PROPERTIES => [
                            'egg' => [
                                self::KEY_TYPE => self::PRIMITIVE
                            ],
                        ],
                    ],
                ],
            ],
            self::KEY_INCLUDE => true,
        ];

    /**
     * @param string $type
     *
     * @return void
     * @throws UnknownFieldType
     */
    public function assertValidType($type)
    {
        if (!in_array($type, $this->validTypes)) {
            throw new UnknownFieldType(
                'Unknown field type: ' . Json::encode($type)
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
    ): array {
        if ($type === ValueTypes::PRIMITIVE) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::PRIMITIVE;
            $otherConfigs[self::KEY_INCLUDE] = $include;

            return $otherConfigs;
        }

        if ($type === ValueTypes::OBJECT) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::OBJECT;
            $otherConfigs[self::KEY_INCLUDE] = $include;
            $otherConfigs[self::KEY_PROPERTIES] = $properties;

            return $otherConfigs;
        }

        if ($type === ValueTypes::PRIMITIVE_COLLECTION) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::PRIMITIVE_COLLECTION;
            $otherConfigs[self::KEY_INCLUDE] = $include;

            return $otherConfigs;
        }

        if ($type === ValueTypes::OBJECT_COLLECTION) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::OBJECT_COLLECTION;
            $otherConfigs[self::KEY_INCLUDE] = $include;
            $otherConfigs[self::KEY_PROPERTIES] = $properties;

            return $otherConfigs;
        }

        if ($type === ValueTypes::COLLECTION_COLLECTION) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::COLLECTION_COLLECTION;
            $otherConfigs[self::KEY_INCLUDE] = $include;
            $otherConfigs[self::KEY_PROPERTIES] = $properties;

            return $otherConfigs;
        }

        if ($type === ValueTypes::COLLECTION) {
            $otherConfigs[self::KEY_TYPE] = ValueTypes::COLLECTION;
            $otherConfigs[self::KEY_INCLUDE] = $include;
            if (!empty($properties)) {
                $otherConfigs[self::KEY_PROPERTIES] = $properties;
            }

            return $otherConfigs;
        }

        static::assertValidType($type);
    }
}
