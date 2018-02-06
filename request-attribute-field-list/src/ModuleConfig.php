<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList;

use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\AssertValidOrderAllowedFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\AssertValidWhereAllowedFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\FilterAllowedFieldListByIncludeKey;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\FilterAllowedFieldListByRequestFieldList;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeFilteredFieldConfigByRequestFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfigBasic;

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

                    FilterAllowedFieldListByIncludeKey::class => [
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],

                    FilterAllowedFieldListByRequestFieldList::class => [],

                    WithRequestAttributeAllowedFieldConfigFromOptions::class => [],

                    WithRequestAttributeFilteredFieldConfigByRequestFields::class => [
                        'arguments' => [
                            FilterAllowedFieldListByRequestFieldList::class,
                            FilterAllowedFieldListByIncludeKey::class,
                        ],
                    ],

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
