<?php

namespace Reliv\PipeRat2\ResponseFormat;

use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetQueryParam;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptType;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptTypeBasic;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattableBasic;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatFile;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatFileData;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJsonAlways;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJsonError;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatXml;

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
                    IsRequestValidAcceptType::class => [
                        'class' => IsRequestValidAcceptTypeBasic::class,
                    ],
                    IsResponseFormattable::class => [
                        'class' => IsResponseFormattableBasic::class,
                    ],
                    IsResponseFormattableBasic::class => [],

                    ResponseFormatFile::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],

                    ResponseFormatFileData::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetQueryParam::class,
                            GetDataModel::class,
                            Extract::class,
                        ],
                    ],

                    ResponseFormatJson::class => [
                        'arguments' => [
                            GetOptions::class,
                            IsResponseFormattable::class,
                            GetDataModel::class
                        ],
                    ],

                    ResponseFormatJsonAlways::class => [
                        'arguments' => [
                            GetOptions::class,
                            IsResponseFormattable::class,
                            GetDataModel::class
                        ],
                    ],

                    ResponseFormatJsonError::class => [
                        'arguments' => [
                            GetOptions::class,
                            IsResponseFormattable::class,
                            GetDataModel::class
                        ],
                    ],

                    ResponseFormatXml::class => [
                        'arguments' => [
                            GetOptions::class,
                            IsResponseFormattable::class,
                            GetDataModel::class
                        ],
                    ],
                ],
            ],
        ];
    }
}
