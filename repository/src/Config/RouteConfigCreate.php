<?php

namespace Reliv\PipeRat2\Repository\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedNotConfigured;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\ValidateNotConfigured;
use Reliv\PipeRat2\DataValidate\Http\RequestDataValidate;
use Reliv\PipeRat2\Repository\Api\CreateNotConfigured;
use Reliv\PipeRat2\Repository\Http\RepositoryCreate;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersFields;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersAdded;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeaders;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigCreate extends RouteConfigAbstract implements RouteConfig
{
    protected static function defaultConfig(): array
    {
        return [
            'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.create',

            'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}',

            'middleware' => [
                RequestFormat::configKey()
                => RequestFormat::class,

                RequestAcl::configKey()
                => RequestAcl::class,

                RequestAttributeUrlEncodedFiltersFields::configKey()
                => RequestAttributeUrlEncodedFiltersFields::class,

                RequestDataValidate::configKey()
                => RequestDataValidate::class,

                /** <response-mutators> */
                ResponseHeaders::configKey()
                => ResponseHeaders::class,

                ResponseFormat::configKey()
                => ResponseFormat::class,

                ResponseDataExtractor::configKey()
                => ResponseDataExtractor::class,
                /** </response-mutators> */

                RepositoryCreate::configKey()
                => RepositoryCreate::class,
            ],
            'options' => [
                RequestFormat::configKey() => [
                    RequestFormat::OPTION_SERVICE_NAME
                    => WithParsedBodyJson::class,

                    RequestFormat::OPTION_SERVICE_OPTIONS => [],
                ],

                RequestAcl::configKey() => [
                    RequestAcl::OPTION_SERVICE_NAME
                    => IsAllowedNotConfigured::class,

                    RequestAcl::OPTION_SERVICE_OPTIONS => [
                        IsAllowedNotConfigured::OPTION_MESSAGE
                        => IsAllowedNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                            . ' in file: "{pipe-rat-2-config.source-config-file}"',
                    ],
                ],

                RequestAttributeUrlEncodedFiltersFields::configKey() => [],

                RequestDataValidate::configKey() => [
                    RequestDataValidate::OPTION_SERVICE_NAME
                    => ValidateNotConfigured::class,

                    RequestDataValidate::OPTION_SERVICE_OPTIONS => [
                        ValidateNotConfigured::OPTION_MESSAGE
                        => ValidateNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                            . ' in file: "{pipe-rat-2-config.source-config-file}"',
                    ],
                ],

                /** <response-mutators> */
                ResponseHeaders::configKey() => [
                    ResponseHeaders::OPTION_SERVICE_NAME
                    => WithResponseHeadersAdded::class,

                    ResponseHeaders::OPTION_SERVICE_OPTIONS => [
                        WithResponseHeadersAdded::OPTION_HEADERS => []
                    ],
                ],

                ResponseFormat::configKey() => [
                    ResponseFormat::OPTION_SERVICE_NAME
                    => WithFormattedResponseJson::class,

                    ResponseFormat::OPTION_SERVICE_OPTIONS => [],
                ],

                ResponseDataExtractor::configKey() => [
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractPropertyGetter::class,
                    ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                        ExtractPropertyGetter::OPTION_PROPERTY_LIST => null,
                        ExtractPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],
                /** </response-mutators> */

                RepositoryCreate::configKey() => [
                    RepositoryCreate::OPTION_SERVICE_NAME
                    => CreateNotConfigured::class,

                    RepositoryCreate::OPTION_SERVICE_OPTIONS => [
                        CreateNotConfigured::OPTION_MESSAGE
                        => CreateNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                            . ' in file: "{pipe-rat-2-config.source-config-file}"',
                    ],
                ],
            ],

            'allowed_methods' => ['POST'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormat::configKey() => 800,
            RequestAcl::configKey() => 700,
            RequestAttributeUrlEncodedFiltersFields::configKey() => 600,
            RequestDataValidate::configKey() => 500,

            /** <response-mutators> */
            ResponseHeaders::configKey() => 400,
            ResponseFormat::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryCreate::configKey() => 100,
        ];
    }
}
