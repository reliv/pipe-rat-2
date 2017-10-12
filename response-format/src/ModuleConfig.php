<?php

namespace Reliv\PipeRat2\ResponseFormat;

use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptType;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptTypeBasic;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattableBasic;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseFile;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseFileData;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseXml;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;

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
                        'arguments' => [
                            IsRequestValidAcceptTypeBasic::DEFAULT_ACCEPTS
                        ],
                    ],
                    IsResponseFormattable::class => [
                        'class' => IsResponseFormattableBasic::class,
                        'arguments' => [
                            IsResponseFormattableBasic::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
                        ],
                    ],

                    WithFormattedResponseFile::class => [
                        'arguments' => [
                            WithFormattedResponseFile::DEFAULT_CONTENT_TYPE
                        ],
                    ],

                    WithFormattedResponseFileData::class => [
                        'arguments' => [
                            IsResponseFormattable::class,
                            GetDataModel::class,
                            ExtractPropertyGetter::class,
                            ['literal' => WithFormattedResponseFileData::DEFAULT_CONTENT_TYPE],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FILE_NAME],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FILE_BASE_64_PROPERTY],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FILE_CONTENT_TYPE_PROPERTY],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FILE_NAME_PROPERTY],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_DOWNLOAD_QUERY_PARAM],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FORCE_DOWNLOAD],
                            ['literal' => WithFormattedResponseFileData::DEFAULT_FORMATTABLE_RESPONSE_CLASSES],
                        ],
                    ],

                    WithFormattedResponseJson::class => [
                        'arguments' => [
                            IsResponseFormattable::class,
                            GetDataModel::class,
                            ['literal' => WithFormattedResponseJson::DEFAULT_CONTENT_TYPE],
                            ['literal' => WithFormattedResponseJson::DEFAULT_JSON_ENCODING_OPTIONS],
                            ['literal' => WithFormattedResponseJson::DEFAULT_FORMATTABLE_RESPONSE_CLASSES],
                        ],
                    ],

                    WithFormattedResponseXml::class => [
                        'arguments' => [
                            IsResponseFormattable::class,
                            GetDataModel::class,
                            ['literal' => WithFormattedResponseXml::DEFAULT_CONTENT_TYPE],
                            ['literal' => WithFormattedResponseXml::DEFAULT_FORMATTABLE_RESPONSE_CLASSES],
                        ],
                    ],

                    ResponseFormat::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            IsRequestValidAcceptType::class,
                            ['literal' => ResponseFormat::DEFAULT_ACCEPTS],
                            ['literal' => ResponseFormat::DEFAULT_NOT_ALLOWED_STATUS_CODE],
                            ['literal' => ResponseFormat::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE],
                        ]
                    ]
                ],
            ],
        ];
    }
}
