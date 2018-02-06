<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Reliv\PipeRat2\Core\Api\ObjectToArray;
use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @todo Not used
 * @author James Jervis - https://github.com/jerv13
 */
class BuildFieldListByDataModel
{
    protected $valueTypes;
    protected $fieldConfig;
    protected $objectToArray;

    /**
     * @param ValueTypes    $valueTypes
     * @param FieldConfig   $fieldConfig
     * @param ObjectToArray $objectToArray
     */
    public function __construct(
        ValueTypes $valueTypes,
        FieldConfig $fieldConfig,
        ObjectToArray $objectToArray
    ) {
        $this->valueTypes = $valueTypes;
        $this->fieldConfig = $fieldConfig;
        $this->objectToArray = $objectToArray;
    }

    /**
     * @param mixed $dataModel
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        $dataModel
    ): array {
        if (empty($dataModel)) {
            return null;
        }

        $valueType = $this->valueTypes->getType($dataModel);

        if ($valueType === ValueTypes::PRIMITIVE) {
            return $this->fieldConfig->buildFieldConfig(
                $valueType
            );
        }

        if ($valueType === ValueTypes::OBJECT) {
            return $this->buildObjectFieldList(
                $dataModel
            );
        }

        if ($valueType === ValueTypes::COLLECTION) {
            return $this->buildCollectionFieldList(
                $dataModel
            );
        }

        throw new UnknownValueType(
            'Unknown field type: ' . $valueType
        );
    }

    /**
     * @param $dataModel
     *
     * @return mixed
     * @throws UnknownFieldType
     * @throws UnknownValueType
     */
    protected function buildObjectFieldList($dataModel)
    {
        $valueArray = $this->objectToArray->__invoke(
            $dataModel
        );

        $fieldList = [];

        foreach ($valueArray as $field => $value) {
            $valueType = $this->valueTypes->getType($dataModel);

            if ($valueType !== ValueTypes::PRIMITIVE) {
                // Skip any deep types
                continue;
            }

            $fieldList[$field] = $this->fieldConfig->buildFieldConfig(
                $valueType,
                [],
                false
            );
        }

        return $fieldList;
    }

    /**
     * @param $dataModel
     *
     * @return array|mixed
     * @throws UnknownFieldType
     * @throws UnknownValueType
     */
    protected function buildCollectionFieldList($dataModel)
    {
        $first = null;

        foreach ($dataModel as $value) {
            $first = $value;
            break;
        }

        $valueType = $this->valueTypes->getType($first);

        if ($valueType === ValueTypes::OBJECT) {
            return $this->buildObjectFieldList(
                $first
            );
        }

        return $this->fieldConfig->buildFieldConfig(
            ValueTypes::PRIMITIVE
        );
    }
}
