<?php

namespace Reliv\PipeRat2\DataFieldList\Api;

use Reliv\PipeRat2\DataFieldList\Exception\FieldNotAllowed;
use Reliv\PipeRat2\DataFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FilterAllowedFieldListByRequestFieldList
{
    /**
     * @param array $allowedFieldList
     * @param array $requestFieldList
     *
     * @return array
     * @throws FieldNotAllowed
     */
    public function __invoke(
        array $allowedFieldList,
        array $requestFieldList
    ): array {
        if (empty($requestFieldList)) {
            return $allowedFieldList;
        }

        return $this->filter(
            $allowedFieldList,
            $requestFieldList
        );
    }

    /**
     * @param array $allowedFieldList
     * @param array $requestFieldList
     *
     * @return array
     * @throws FieldNotAllowed
     */
    public function filter(
        array $allowedFieldList,
        array $requestFieldList
    ): array {
        $fieldListFiltered = [];

        foreach ($requestFieldList as $fieldName => $value) {
            // IF no whitelist config, then error
            if (!array_key_exists($fieldName, $allowedFieldList)) {
                throw new FieldNotAllowed(
                    'Field is not allowed for: ' . $fieldName
                );
            }

            if (!is_array($requestFieldList[$fieldName]) && !is_bool($requestFieldList[$fieldName])) {
                throw new FieldNotAllowed(
                    'Field request must be bool or array: ' . $fieldName
                );
            }

            // If field is true, then include it
            if ($requestFieldList[$fieldName] === true) {
                $fieldListFiltered[$fieldName] = $allowedFieldList[$fieldName];
                $fieldListFiltered[$fieldName][FieldConfig::KEY_INCLUDE] = true;
                continue;
            }

            // If field is false, then do not include it
            if ($requestFieldList[$fieldName] === false) {
                continue;
            }

            // is array, recurse
            $fieldListFiltered[$fieldName] = $this->filter(
                $allowedFieldList[$fieldName],
                $value
            );
        }

        return $fieldListFiltered;
    }
}
