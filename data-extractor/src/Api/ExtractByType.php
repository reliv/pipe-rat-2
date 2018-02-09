<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExtractByType implements Extract
{
    const OPTION_CONTEXT = 'context';
    const DEFAULT_CONTEXT = 'unknown';

    protected $valueTypes;
    protected $fieldConfig;
    protected $extractObjectProperty;

    protected $methodMap
        = [
            FieldConfig::PRIMITIVE => 'extractPrimitive',
            FieldConfig::OBJECT => 'extractObject',
            FieldConfig::COLLECTION => 'extractCollection',
            FieldConfig::PRIMITIVE_COLLECTION => 'extractPrimitiveCollection',
            FieldConfig::OBJECT_COLLECTION => 'extractObjectCollection',
            FieldConfig::COLLECTION_COLLECTION => 'extractCollectionCollection',
        ];

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
        $fieldConfig[self::OPTION_CONTEXT] = '**root**';

        return $this->extract(
            $dataModel,
            $fieldConfig
        );
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|object|string
     * @throws \Exception
     */
    protected function extract(
        $dataModel,
        array $fieldConfig = []
    ) {
        // NOTE: Assumes primitive if no value found
        $type = $this->fieldConfig->getType(
            $fieldConfig,
            FieldConfig::PRIMITIVE
        );

        if (!array_key_exists($type, $this->methodMap)) {
            throw new InvalidFieldType(
                'Could not get field type for data model'
                . ' with type: (' . $type . ')'
            );
        }

        $method = $this->methodMap[$type];

        return $this->$method(
            $dataModel,
            $fieldConfig
        );
    }

    /**
     * @param array|object $dataModel
     * @param array        $fieldConfig
     *
     * @return array|bool|int|null|object|string
     * @throws \Exception
     */
    public function extractPrimitive(
        $dataModel,
        array $fieldConfig = []
    ) {
        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::PRIMITIVE,
            $context
        );

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

        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::OBJECT,
            $context
        );

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::OBJECT
        );

        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            throw new InvalidFieldConfig(
                'Object extractor requires a property list'
                . ' in context: (' . $context . ')'
                . ' for field config: ' . Json::encode($fieldConfig)
            );
        }

        $properties = $this->fieldConfig->getProperties($fieldConfig);

        $array = [];

        foreach ($properties as $fieldName => $propertyFieldConfig) {
            $rawValue = $this->extractObjectProperty->__invoke(
                $fieldName,
                $dataModel
            );
            $propertyFieldConfig[self::OPTION_CONTEXT] = $fieldName;
            $array[$fieldName] = $this->extract(
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
    public function extractPrimitiveCollection(
        $dataModel,
        array $fieldConfig = []
    ) {
        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::PRIMITIVE_COLLECTION
        );

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::PRIMITIVE_COLLECTION
        );

        $array = [];
        foreach ($dataModel as $value) {
            $array[] = $this->extractPrimitive(
                $value,
                $this->fieldConfig->buildFieldConfig(
                    FieldConfig::PRIMITIVE,
                    [],
                    true,
                    [self::OPTION_CONTEXT => $context]
                )
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
    public function extractObjectCollection(
        $dataModel,
        array $fieldConfig = []
    ) {
        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::OBJECT_COLLECTION
        );

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::OBJECT_COLLECTION
        );

        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            throw new InvalidFieldConfig(
                'Object collection extractor requires a property list'
                . ' in context: (' . $context . ')'
                . ' for field config: ' . Json::encode($fieldConfig)
            );
        }

        $properties = $this->fieldConfig->getProperties($fieldConfig);
        $array = [];

        foreach ($dataModel as $object) {
            $array[] = $this->extractObject(
                $object,
                $this->fieldConfig->buildFieldConfig(
                    FieldConfig::OBJECT,
                    $properties,
                    true,
                    [self::OPTION_CONTEXT => $context]
                )
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
    public function extractCollectionCollection(
        $dataModel,
        array $fieldConfig = []
    ) {
        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::COLLECTION_COLLECTION
        );

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::COLLECTION_COLLECTION
        );

        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            throw new InvalidFieldConfig(
                'Object collection-collection extractor requires field-config as properties'
                . ' in context: (' . $context . ')'
                . ' for field config: ' . Json::encode($fieldConfig)
            );
        }

        $subSubFieldConfig = $this->fieldConfig->getProperties($fieldConfig);

        if (!$this->fieldConfig->hasType($subSubFieldConfig)) {
            throw new InvalidFieldConfig(
                'Object collection-collection extractor requires field-config as properties to have type'
                . ' in context: (' . $context . ')'
                . ' for field config: ' . Json::encode($fieldConfig)
            );
        }

        $subSubType = $this->fieldConfig->getType($subSubFieldConfig);
        $subSubProperties = $this->fieldConfig->getProperties($subSubFieldConfig);

        $array = [];

        foreach ($dataModel as $subCollection) {
            $subArray = [];
            foreach ($subCollection as $subSubCollection) {
                $subArray[] = $this->extract(
                    $subSubCollection,
                    $this->fieldConfig->buildFieldConfig(
                        $subSubType,
                        $subSubProperties,
                        true,
                        [self::OPTION_CONTEXT => $context]
                    )
                );
            }

            $array[] = $subArray;
        }

        return $array;
    }

    /**
     * @deprecated
     *
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
        $context = Options::get(
            $fieldConfig,
            self::OPTION_CONTEXT,
            self::DEFAULT_CONTEXT
        );

        $this->valueTypes->assertType(
            $dataModel,
            ValueTypes::COLLECTION
        );

        $this->fieldConfig->assertIsType(
            $fieldConfig,
            FieldConfig::COLLECTION
        );

        // Primitive
        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            return $this->extractPrimitiveCollection(
                $dataModel,
                $this->fieldConfig->buildFieldConfig(
                    FieldConfig::PRIMITIVE_COLLECTION,
                    [],
                    true,
                    [self::OPTION_CONTEXT => $context]
                )
            );
        }

        // Object
        return $this->extractObjectCollection(
            $dataModel,
            $this->fieldConfig->buildFieldConfig(
                FieldConfig::OBJECT_COLLECTION,
                $this->fieldConfig->getProperties($fieldConfig),
                true,
                [self::OPTION_CONTEXT => $context]
            )
        );

        // NOTE: collection-collection not supported
    }
}
