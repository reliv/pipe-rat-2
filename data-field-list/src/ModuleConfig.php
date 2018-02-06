<?php

namespace Reliv\PipeRat2\DataFieldList;

use Reliv\PipeRat2\DataFieldList\Api\AssertValidOrderAllowedFields;
use Reliv\PipeRat2\DataFieldList\Api\AssertValidWhereAllowedFields;
use Reliv\PipeRat2\DataFieldList\Api\ObjectToArray;
use Reliv\PipeRat2\DataFieldList\Api\ObjectToArrayBasic;
use Reliv\PipeRat2\DataFieldList\Api\WithRequestAttributeAllowedFieldsFromOptions;
use Reliv\PipeRat2\DataFieldList\Service\FieldConfig;
use Reliv\PipeRat2\DataFieldList\Service\FieldConfigBasic;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;

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
                    // @override
                    AssertValidWhere::class => [
                        'class' => AssertValidWhereAllowedFields::class
                    ],
                    // @override
                    AssertValidOrder::class => [
                        'class' => AssertValidOrderAllowedFields::class
                    ],

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
