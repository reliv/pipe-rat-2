<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList;

use Reliv\PipeRat2\RequestAttribute\Api\AssertValidFields;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\AssertValidFieldsAllowedFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\AssertValidOrderAllowedFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\AssertValidWhereAllowedFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\FilterAllowedFieldListByIncludeKey;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\FilterAllowedFieldListByRequestFieldList;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfigByRequestFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfigFromOptions;
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
                    AssertValidFields::class => [
                        'class' => AssertValidFieldsAllowedFields::class,
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],
                    // @override
                    AssertValidWhere::class => [
                        'class' => AssertValidWhereAllowedFields::class,
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],
                    // @override
                    AssertValidOrder::class => [
                        'class' => AssertValidOrderAllowedFields::class,
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],

                    FilterAllowedFieldListByIncludeKey::class => [
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],

                    FilterAllowedFieldListByRequestFieldList::class => [
                        'arguments' => [
                            FieldConfig::class,
                        ],
                    ],

                    WithRequestAttributeAllowedFieldConfigFromOptions::class => [],

                    WithRequestAttributeExtractorFieldConfigByRequestFields::class => [
                        'arguments' => [
                            FilterAllowedFieldListByRequestFieldList::class,
                            FilterAllowedFieldListByIncludeKey::class,
                        ],
                    ],

                    WithRequestAttributeExtractorFieldConfigFromOptions::class => [],

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
