<?php

namespace Reliv\PipeRat2\DataFieldList;

use Reliv\PipeRat2\DataFieldList\Api\ObjectToArray;
use Reliv\PipeRat2\DataFieldList\Api\ObjectToArrayBasic;
use Reliv\PipeRat2\DataFieldList\Api\WithRequestAttributeAllowedFieldsFromOptions;
use Reliv\PipeRat2\DataFieldList\Service\FieldConfig;
use Reliv\PipeRat2\DataFieldList\Service\FieldConfigBasic;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    ObjectToArray::class => [
                        'class' => ObjectToArrayBasic::class
                    ],

                    WithRequestAttributeAllowedFieldsFromOptions::class => [],

                    /**
                     * Service
                     */
                    FieldConfig::class => [
                        'class' => FieldConfigBasic::class
                    ],
                ],
            ],
        ];
    }
}
