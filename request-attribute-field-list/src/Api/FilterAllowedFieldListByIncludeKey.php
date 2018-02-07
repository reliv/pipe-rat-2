<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

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
     */
    public function __invoke(
        array $fieldConfig
    ): array {
        $filteredConfig = $this->filterConfig($fieldConfig);

        if ($filteredConfig === null) {
            return [];
        }

        return $filteredConfig;
    }

    /**
     * @param array $fieldConfig
     *
     * @return array|null
     */
    public function filterConfig(
        array $fieldConfig
    ) {
        if (!$this->fieldConfig->canInclude($fieldConfig)) {
            return null;
        }

        if ($this->fieldConfig->hasProperties($fieldConfig)) {
            return $this->filter(
                $this->fieldConfig->getProperties(
                    $fieldConfig
                )
            );
        }

        return $fieldConfig;
    }

    /**
     * @param array $fieldProperties
     *
     * @return array
     */
    public function filter(
        array $fieldProperties
    ): array {
        $fieldListFiltered = [];
        foreach ($fieldProperties as $fieldName => $fieldConfig) {
            $filteredConfig = $this->filterConfig($fieldConfig);

            if ($filteredConfig === null) {
                continue;
            }

            $fieldListFiltered[$fieldName] = $filteredConfig;
        }

        return $fieldListFiltered;
    }
}
