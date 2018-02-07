<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExtractByType implements Extract
{
    protected $valueTypes;
    protected $fieldConfig;
    protected $extractObjectProperty;

    /**
     * @param ValueTypes            $valueTypes
     * @param FieldConfig           $fieldConfig
     * @param ExtractObjectProperty $extractObjectProperty
     */
    public function __construct(
        ValueTypes $valueTypes,
        FieldConfig $fieldConfig,
        ExtractObjectProperty $extractObjectProperty
    ) {
        $this->valueTypes = $valueTypes;
        $this->fieldConfig = $fieldConfig;
        $this->extractObjectProperty = $extractObjectProperty;
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|object|string
     * @throws \Exception
     */
    public function __invoke(
        $dataModel,
        array $fieldConfig = []
    ) {
        // NOTE: Assumes primitive if no value found
        $type = $this->fieldConfig->getType(
            $fieldConfig,
            FieldConfig::PRIMITIVE
        );

        if ($type === FieldConfig::PRIMITIVE) {
            return $this->extractPrimitive(
                $dataModel,
                $fieldConfig
            );
        }

        if ($type === FieldConfig::OBJECT) {
            return $this->extractObject(
                $dataModel,
                $fieldConfig
            );
        }

        if ($type === FieldConfig::COLLECTION) {
            return $this->extractCollection(
                $dataModel,
                $fieldConfig
            );
        }

        throw new InvalidFieldType(
            'Could not get field type for data model'
        );
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|object|string|InvalidFieldType
     * @throws \Exception
     */
    public function extractPrimitive(
        $dataModel,
        array $fieldConfig = []
    ) {
        if (!$this->canExtract($dataModel, ValueTypes::PRIMITIVE)) {
            return new InvalidFieldType(
                'Can not extract type: ' . FieldConfig::PRIMITIVE
                . ' from value that is type: ' . $this->getValueType($dataModel)
            );
        }
        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::PRIMITIVE
        );

        return $dataModel;
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|string
     * @throws \Exception
     */
    public function extractObject(
        $dataModel,
        array $fieldConfig = []
    ) {
        // null object - nothing to extract
        if ($dataModel === null) {
            return null;
        }

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::OBJECT
        );

        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            throw new InvalidFieldConfig(
                'Object extractor requires a property list'
            );
        }

        $properties = $this->fieldConfig->getProperties($fieldConfig);

        $array = [];

        foreach ($properties as $fieldName => $propertyFieldConfig) {
            $rawValue = $this->extractObjectProperty->__invoke(
                $fieldName,
                $dataModel
            );
            $array[$fieldName] = $this->__invoke(
                $rawValue,
                $propertyFieldConfig
            );
        }

        return $array;
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|string
     * @throws \Exception
     */
    public function extractCollection(
        $dataModel,
        array $fieldConfig = []
    ) {
        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::COLLECTION
        );

        // Primitive
        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            $array = [];
            foreach ($dataModel as $value) {
                $array[] = $this->extractPrimitive(
                    $value,
                    [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                    ]
                );
            }

            return $array;
        }

        // If we have property list, must be a collection of objects
        $properties = $this->fieldConfig->getProperties($fieldConfig);
        $array = [];

        foreach ($dataModel as $object) {
            $array[] = $this->extractObject(
                $object,
                [
                    FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                    FieldConfig::KEY_PROPERTIES => $properties
                ]
            );
        }

        return $array;
    }

    /**
     * @param $dataModel
     *
     * @return bool
     * @throws UnknownValueType
     */
    protected function getValueType(
        $dataModel
    ): bool {
        return $this->valueTypes->getType(
            $dataModel
        );
    }

    /**
     * @param $dataModel
     * @param $valueType
     *
     * @return bool
     * @throws UnknownValueType
     */
    protected function canExtract(
        $dataModel,
        $valueType
    ): bool {
        return $this->valueTypes->isType(
            $dataModel,
            $valueType
        );
    }
}
