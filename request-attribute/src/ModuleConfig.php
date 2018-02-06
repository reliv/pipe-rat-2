<?php

namespace Reliv\PipeRat2\RequestAttribute;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServicesFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServicesOptionsFromConfigOptions;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrderValues;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhereNoDeepWheres;
use Reliv\PipeRat2\RequestAttribute\Api\GetUrlEncodedFilterValue;
use Reliv\PipeRat2\RequestAttribute\Api\QueryParamValueDecode;
use Reliv\PipeRat2\RequestAttribute\Api\QueryParamValueDecodeJson;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedLimit;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedOrder;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedSkip;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhereMutatorNoop;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttribute;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;

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
                    AssertValidWhere::class => [
                        'class' => AssertValidWhereNoDeepWheres::class
                    ],

                    AssertValidOrder::class => [
                        'class' => AssertValidOrderValues::class
                    ],

                    GetUrlEncodedFilterValue::class => [
                        'arguments' => [
                            QueryParamValueDecode::class,
                        ],
                    ],

                    QueryParamValueDecode::class => [
                        'class' => QueryParamValueDecodeJson::class,
                    ],

                    WithRequestAttributeUrlEncodedFields::class => [
                        'arguments' => [
                            GetUrlEncodedFilterValue::class,
                        ],
                    ],

                    WithRequestAttributeUrlEncodedLimit::class => [
                        'arguments' => [
                            GetUrlEncodedFilterValue::class,
                        ],
                    ],

                    WithRequestAttributeUrlEncodedOrder::class => [
                        'arguments' => [
                            GetUrlEncodedFilterValue::class,
                        ],
                    ],

                    WithRequestAttributeUrlEncodedSkip::class => [
                        'arguments' => [
                            GetUrlEncodedFilterValue::class,
                        ],
                    ],

                    WithRequestAttributeUrlEncodedWhere::class => [
                        'arguments' => [
                            GetUrlEncodedFilterValue::class,
                            ['literal' => WithRequestAttributeUrlEncodedWhere::DEFAULT_ALLOW_DEEP_WHERES],
                        ],
                    ],

                    WithRequestAttributeWhereMutatorNoop::class => [],

                    RequestAttribute::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                        ],
                    ],

                    RequestAttributes::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServicesFromConfigOptions::class,
                            GetServicesOptionsFromConfigOptions::class,
                            ['literal' => RequestAttributes::DEFAULT_SERVICE_NAMES],
                            ['literal' => RequestAttributes::DEFAULT_SERVICE_NAMES_OPTIONS],
                        ],
                    ],
                ],
            ],
        ];
    }
}
