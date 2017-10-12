<?php

namespace Reliv\PipeRat2\Repository\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedNotConfigured;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractCollectionPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\Repository\Api\FindNotConfigured;
use Reliv\PipeRat2\Repository\Http\RepositoryFind;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersFields;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersLimit;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersOrder;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersSkip;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersWhere;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigFind extends RouteConfigAbstract implements RouteConfig
{
    protected static function defaultConfig(): array
    {
        return [
            'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.find',

            'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}',

            'middleware' => [
                RequestFormat::configKey()
                => RequestFormat::class,

                RequestAcl::configKey()
                => RequestAcl::class,

                RequestAttributeUrlEncodedFiltersWhere::configKey()
                => RequestAttributeUrlEncodedFiltersWhere::class,

                RequestAttributeUrlEncodedFiltersFields::configKey()
                => RequestAttributeUrlEncodedFiltersFields::class,

                RequestAttributeUrlEncodedFiltersOrder::configKey()
                => RequestAttributeUrlEncodedFiltersOrder::class,

                RequestAttributeUrlEncodedFiltersSkip::configKey()
                => RequestAttributeUrlEncodedFiltersSkip::class,

                RequestAttributeUrlEncodedFiltersLimit::configKey()
                => RequestAttributeUrlEncodedFiltersLimit::class,

                /** <response-mutators> */
                ResponseHeadersAdd::configKey()
                => ResponseHeadersAdd::class,

                ResponseFormat::configKey()
                => ResponseFormat::class,

                ResponseDataExtractor::configKey()
                => ResponseDataExtractor::class,
                /** </response-mutators> */

                RepositoryFind::configKey()
                => RepositoryFind::class,
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

                RequestAttributeUrlEncodedFiltersWhere::configKey() => [
                    RequestAttributeUrlEncodedFiltersWhere::OPTION_ALLOW_DEEP_WHERES => false,
                ],

                RequestAttributeUrlEncodedFiltersFields::configKey() => [],

                RequestAttributeUrlEncodedFiltersOrder::configKey() => [],

                RequestAttributeUrlEncodedFiltersSkip::configKey() => [],

                RequestAttributeUrlEncodedFiltersLimit::configKey() => [],

                /** <response-mutators> */
                ResponseHeadersAdd::configKey() => [
                    ResponseHeadersAdd::OPTION_HEADERS
                    => [],
                ],

                ResponseFormat::configKey() => [
                    ResponseFormat::OPTION_SERVICE_NAME
                    => WithFormattedResponseJson::class,

                    ResponseFormat::OPTION_SERVICE_OPTIONS => [],
                ],

                ResponseDataExtractor::configKey() => [
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractCollectionPropertyGetter::class,
                    ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                        ExtractCollectionPropertyGetter::OPTION_PROPERTY_LIST => null,
                        ExtractCollectionPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],
                /** </response-mutators> */

                RepositoryFind::configKey() => [
                    RepositoryFind::OPTION_SERVICE_NAME
                    => FindNotConfigured::class,

                    RepositoryFind::OPTION_SERVICE_OPTIONS => [
                        FindNotConfigured::OPTION_MESSAGE
                        => FindNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                            . ' in file: "{pipe-rat-2-config.source-config-file}"',
                    ],
                ],
            ],

            'allowed_methods' => ['GET'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormat::configKey() => 1100,
            RequestAcl::configKey() => 1000,
            RequestAttributeUrlEncodedFiltersWhere::configKey() => 900,
            RequestAttributeUrlEncodedFiltersFields::configKey() => 800,
            RequestAttributeUrlEncodedFiltersOrder::configKey() => 700,
            RequestAttributeUrlEncodedFiltersSkip::configKey() => 600,
            RequestAttributeUrlEncodedFiltersLimit::configKey() => 500,

            /** <response-mutators> */
            ResponseHeadersAdd::configKey() => 400,
            ResponseFormat::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryFind::configKey() => 100,
        ];
    }
}
