<?php

namespace Reliv\PipeRat2\Repository\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedNotConfigured;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractByType;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\ValidateNotConfigured;
use Reliv\PipeRat2\DataValidate\Http\RequestDataValidate;
use Reliv\PipeRat2\Repository\Api\UpsertNotConfigured;
use Reliv\PipeRat2\Repository\Http\RepositoryUpsert;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestValidAttributesAsserts;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributesValidate;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfigByRequestFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersAdded;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeaders;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigUpsert extends RouteConfigAbstract implements RouteConfig
{
    protected static function defaultConfig(): array
    {
        return [
            'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.upsert',

            'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}',

            'middleware' => [
                RequestFormat::configKey()
                => RequestFormat::class,

                RequestAcl::configKey()
                => RequestAcl::class,

                RequestAttributes::configKey()
                => RequestAttributes::class,

                RequestAttributesValidate::configKey()
                => RequestAttributesValidate::class,

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

                RepositoryUpsert::configKey()
                => RepositoryUpsert::class,
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

                RequestAttributes::configKey() => [
                    RequestAttributes::OPTION_SERVICE_NAMES => [
                        WithRequestAttributeFields::class
                        => WithRequestAttributeUrlEncodedFields::class,

                        WithRequestAttributeAllowedFieldConfig::class
                        => WithRequestAttributeAllowedFieldConfigFromOptions::class,

                        WithRequestAttributeExtractorFieldConfig::class
                        => WithRequestAttributeExtractorFieldConfigByRequestFields::class,
                    ],

                    RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                        WithRequestAttributeAllowedFieldConfig::class => [
                            WithRequestAttributeAllowedFieldConfigFromOptions::OPTION_ALLOWED_FIELDS
                            /* @todo Over-ride with YOUR FieldsConfig */
                            => [
                                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                                FieldConfig::KEY_PROPERTIES => [],
                                FieldConfig::KEY_INCLUDE => true,
                            ],
                        ]
                    ],
                ],

                RequestAttributesValidate::configKey() => [
                    RequestAttributesValidate::OPTION_SERVICE_NAME
                    => WithRequestValidAttributesAsserts::class,
                ],

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
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractByType::class,
                ],
                /** </response-mutators> */

                RepositoryUpsert::configKey() => [
                    RepositoryUpsert::OPTION_SERVICE_NAME
                    => UpsertNotConfigured::class,

                    RepositoryUpsert::OPTION_SERVICE_OPTIONS => [
                        UpsertNotConfigured::OPTION_MESSAGE
                        => UpsertNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                            . ' in file: "{pipe-rat-2-config.source-config-file}"',
                    ],
                ],
            ],

            'allowed_methods' => ['PUT'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormat::configKey() => 900,
            RequestAcl::configKey() => 800,
            RequestAttributes::configKey() => 700,
            RequestAttributesValidate::configKey() => 600,
            RequestDataValidate::configKey() => 500,

            /** <response-mutators> */
            ResponseHeaders::configKey() => 400,
            ResponseFormat::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryUpsert::configKey() => 100,
        ];
    }
}
