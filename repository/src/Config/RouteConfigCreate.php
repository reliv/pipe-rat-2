<?php

namespace Reliv\PipeRat2\Repository\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedNotConfigured;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\ValidateNotConfigured;
use Reliv\PipeRat2\DataValidate\Http\RequestValidateMiddleware;
use Reliv\PipeRat2\Repository\Api\CreateNotConfigured;
use Reliv\PipeRat2\Repository\Http\RepositoryCreate;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersFields;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigCreate extends RouteConfigAbstract implements RouteConfig
{
    protected static function defaultConfig(): array
    {
        return [
            'name' => '[--{root-path}--].[--{resource-name}--].create',

            'path' => '[--{root-path}--]/[--{resource-name}--]',

            'middleware' => [
                RequestFormatJson::configKey()
                => RequestFormatJson::class,

                RequestAclMiddleware::configKey()
                => RequestAclMiddleware::class,

                RequestAttributeUrlEncodedFiltersFields::configKey()
                => RequestAttributeUrlEncodedFiltersFields::class,

                RequestValidateMiddleware::configKey()
                => RequestValidateMiddleware::class,

                /** <response-mutators> */
                ResponseHeadersAdd::configKey()
                => ResponseHeadersAdd::class,

                ResponseFormatJson::configKey()
                => ResponseFormatJson::class,

                ResponseDataExtractor::configKey()
                => ResponseDataExtractor::class,
                /** </response-mutators> */

                RepositoryCreate::configKey()
                => RepositoryCreate::class,
            ],
            'options' => [
                RequestFormatJson::configKey() => [
                    RequestFormatJson::OPTION_VALID_CONTENT_TYPES => ['application/json'],
                ],

                RequestAclMiddleware::configKey() => [
                    RequestAclMiddleware::OPTION_SERVICE_NAME
                    => IsAllowedNotConfigured::class,

                    RequestAclMiddleware::OPTION_SERVICE_OPTIONS => [
                        IsAllowedNotConfigured::OPTION_MESSAGE
                        => IsAllowedNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "[--{resource-name}--]"'
                            . ' in file: "[--{source-config-file}--]"',
                    ],
                ],

                RequestAttributeUrlEncodedFiltersFields::configKey() => [],

                RequestValidateMiddleware::configKey() => [
                    RequestValidateMiddleware::OPTION_SERVICE_NAME
                    => ValidateNotConfigured::class,

                    RequestValidateMiddleware::OPTION_SERVICE_OPTIONS => [
                        ValidateNotConfigured::OPTION_MESSAGE
                        => ValidateNotConfigured::DEFAULT_MESSAGE
                            . ' for pipe-rat-2 resource: "[--{resource-name}--]"'
                            . ' in file: "[--{source-config-file}--]"',
                    ],
                ],

                /** <response-mutators> */
                ResponseHeadersAdd::configKey() => [
                    ResponseHeadersAdd::OPTION_HEADERS
                    => [],
                ],

                ResponseFormatJson::configKey() => [
                    ResponseFormatJson::OPTION_JSON_ENCODING_OPTIONS => JSON_PRETTY_PRINT,
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
                            . ' for pipe-rat-2 resource: "[--{resource-name}--]"'
                            . ' in file: "[--{source-config-file}--]"',
                    ],
                ],
            ],

            'allowed_methods' => ['POST'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormatJson::configKey() => 800,
            RequestAclMiddleware::configKey() => 700,
            RequestAttributeUrlEncodedFiltersFields::configKey() => 600,
            RequestValidateMiddleware::configKey() => 500,

            /** <response-mutators> */
            ResponseHeadersAdd::configKey() => 400,
            ResponseFormatJson::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryCreate::configKey() => 100,
        ];
    }
}
