<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FilterAllowedFieldListByIncludeKey
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
     * @param array $fieldConfig
     *
     * @return array
     * @throws UnknownFieldType
     */
    public function __invoke(
        array $fieldConfig
    ): array {
        return $this->filter(
            $fieldConfig
        );
    }

    /**
     * @param array $fieldConfig
     *
     * @return array
     * @throws UnknownFieldType
     */
    public function filter(
        array $fieldConfig
    ): array {
        if (!$this->fieldConfig->canInclude($fieldConfig)) {
            return [];
        }

        if (!$this->fieldConfig->hasProperties($fieldConfig)) {
            return $fieldConfig;
        }

        $fieldConfigProperties = $this->fieldConfig->getProperties($fieldConfig);

        $fieldConfigPropertiesFiltered = [];

        foreach ($fieldConfigProperties as $fieldName => $subFieldConfig) {
            if (!$this->fieldConfig->canInclude($subFieldConfig)) {
                continue;
            }

            // recurse
            $fieldConfigPropertiesFiltered[$fieldName] = $this->filter(
                $subFieldConfig
            );
        }

        return [
            FieldConfig::KEY_TYPE => $this->fieldConfig->getType($fieldConfig),
            FieldConfig::KEY_PROPERTIES => $fieldConfigPropertiesFiltered,
            FieldConfig::KEY_INCLUDE => true,
        ];
    }
}
