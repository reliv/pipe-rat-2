<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\FieldNotAllowed;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FilterAllowedFieldListByRequestFieldList
{
    protected $fieldConfig;

    /**
     * @param FieldConfig $fieldConfig
     */
    public function __construct(
        FieldConfig $fieldConfig
    ) {
        $this->fieldConfig = $fieldConfig;
    }

    /**
     * @param array $allowedFieldConfig
     * @param array $requestFieldList
     *
     * @return array
     * @throws FieldNotAllowed
     * @throws UnknownFieldType
     */
    public function __invoke(
        array $allowedFieldConfig,
        array $requestFieldList
    ): array {
        return $this->filter(
            $allowedFieldConfig,
            $requestFieldList
        );
    }

    /**
     * @param array $allowedFieldConfig
     * @param array $requestFieldList
     *
     * @return array
     * @throws FieldNotAllowed
     * @throws UnknownFieldType
     */
    public function filter(
        array $allowedFieldConfig,
        array $requestFieldList
    ): array {
        // not fields requested
        if (empty($requestFieldList)) {
            return $allowedFieldConfig;
        }

        if (!$this->fieldConfig->hasProperties($allowedFieldConfig)) {
            return $allowedFieldConfig;
        }

        $allowedFieldConfigProperties = $this->fieldConfig->getProperties($allowedFieldConfig);

        $allowedFieldConfigPropertiesFiltered = [];

        foreach ($allowedFieldConfigProperties as $fieldName => $subFieldConfig) {
            if (!array_key_exists($fieldName, $requestFieldList)) {
                $allowedFieldConfigPropertiesFiltered[$fieldName] = $subFieldConfig;
                continue;
            }

            if (is_bool($requestFieldList[$fieldName])) {
                $allowedFieldConfigPropertiesFiltered[$fieldName] = $subFieldConfig;
                $allowedFieldConfigPropertiesFiltered[$fieldName][FieldConfig::KEY_INCLUDE] = $requestFieldList[$fieldName];
                continue;
            }

            // recurse
            if (is_array($requestFieldList[$fieldName])) {
                $allowedFieldConfigPropertiesFiltered[$fieldName] = $this->filter(
                    $subFieldConfig,
                    $requestFieldList[$fieldName]
                );
            }
        }

        return [
            FieldConfig::KEY_TYPE => $this->fieldConfig->getType($allowedFieldConfig),
            FieldConfig::KEY_PROPERTIES => $allowedFieldConfigPropertiesFiltered,
            FieldConfig::KEY_INCLUDE => $this->fieldConfig->canInclude($allowedFieldConfig),
        ];
    }
}
