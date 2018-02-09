<?php

namespace Reliv\PipeRat2\DataExtractor;

use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\ObjectToArray;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractByType;
use Reliv\PipeRat2\DataExtractor\Api\ExtractNoop;
use Reliv\PipeRat2\DataExtractor\Api\ExtractObjectProperty;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

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
                    Extract::class => [
                        'class' => ExtractByType::class,
                        'arguments' => [
                            ValueTypes::class,
                            FieldConfig::class,
                            ExtractObjectProperty::class
                        ]
                    ],

                    ExtractByType::class => [
                        'arguments' => [
                            ValueTypes::class,
                            FieldConfig::class,
                            ExtractObjectProperty::class
                        ]
                    ],

                    ExtractObjectProperty::class => [
                        'arguments' => [
                            ObjectToArray::class,
                        ]
                    ],

                    ExtractNoop::class => [],

                    ResponseDataExtractor::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            GetDataModel::class,
                            BuildFailDataResponse::class,
                            ResponseWithDataBody::class
                        ]
                    ],
                ],
            ],
        ];
    }
}
