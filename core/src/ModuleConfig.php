<?php

namespace Reliv\PipeRat2\Core;

use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetDataModelDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetOptionsExpressiveRoute;
use Reliv\PipeRat2\Core\Api\GetQueryParam;
use Reliv\PipeRat2\Core\Api\GetQueryParamBasic;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptionsBasicFactory;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptionsBasic;
use Reliv\PipeRat2\Core\Api\GetServicesFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServicesFromConfigOptionsBasic;
use Reliv\PipeRat2\Core\Api\GetServicesOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServicesOptionsFromConfigOptionsBasic;
use Reliv\PipeRat2\Core\Api\ObjectToArray;
use Reliv\PipeRat2\Core\Api\ObjectToArrayBasic;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBodyDataResponse;

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
                    GetDataModel::class => [
                        'class' => GetDataModelDataResponse::class,
                    ],
                    GetDataModelDataResponse::class => [],

                    GetOptions::class => [
                        'class' => GetOptionsExpressiveRoute::class,
                    ],
                    GetQueryParam::class => [
                        'class' => GetQueryParamBasic::class,
                    ],

                    GetServiceFromConfigOptions::class => [
                        'factory' => GetServiceFromConfigOptionsBasicFactory::class,
                    ],

                    GetServiceOptionsFromConfigOptions::class => [
                        'class' => GetServiceOptionsFromConfigOptionsBasic::class,
                    ],

                    GetServiceOptionsFromConfigOptionsBasic::class => [],

                    GetServicesFromConfigOptions::class => [
                        'class' => GetServicesFromConfigOptionsBasic::class,
                        'arguments' => [
                            GetServiceFromConfigOptions::class
                        ]
                    ],

                    GetServicesOptionsFromConfigOptions::class => [
                        'class' => GetServicesOptionsFromConfigOptionsBasic::class,
                        'arguments' => [
                            GetServiceOptionsFromConfigOptionsBasic::class
                        ]
                    ],

                    ObjectToArray::class => [
                        'class' => ObjectToArrayBasic::class
                    ],

                    ResponseWithDataBody::class => [
                        'class' => ResponseWithDataBodyDataResponse::class
                    ],

                    ResponseWithDataBodyDataResponse::class => [],
                ],
            ],
        ];
    }
}
