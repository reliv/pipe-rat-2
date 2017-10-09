<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\ValidateNotConfigured;
use Reliv\PipeRat2\DataValidate\Http\RequestValidateMiddleware;
use Reliv\PipeRat2\Repository\Http\RepositoryUpsert;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersFields;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigUpsert extends RouteConfigAbstract implements RouteConfig
{
    protected static function requiredParams(): array
    {
        $requiredParams = parent::requiredParams();
        $requiredParams[] = 'entity-class';

        return $requiredParams;
    }

    protected static function defaultConfig(): array
    {
        return [
            /* Use standard route names for client simplicity */
            'name' => '[--{root-path}--].[--{resource-name}--].upsert',

            /* Use standard route paths for client simplicity */
            'path' => '[--{root-path}--]/[--{resource-name}--]',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
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

                RepositoryUpsert::configKey()
                => RepositoryUpsert::class,
            ],

            /* Use route to find options at runtime */
            'options' => [
                /*'{config-key}' => ['{optionKey}'=>'{optionValue}'],*/
                RequestFormatJson::configKey() => [
                    RequestFormatJson::OPTION_VALID_CONTENT_TYPES => ['application/json'],
                ],

                RequestAclMiddleware::configKey() => [
                    RequestAclMiddleware::OPTION_SERVICE_NAME
                    => IsAllowedRcmUser::class,

                    RequestAclMiddleware::OPTION_SERVICE_OPTIONS => [
                        IsAllowedRcmUser::OPTION_RESOURCE_ID => 'sites',
                        IsAllowedRcmUser::OPTION_PRIVILEGE => 'admin',
                    ],
                ],

                RequestAttributeUrlEncodedFiltersFields::configKey() => [],

                RequestValidateMiddleware::configKey() => [
                    RequestValidateMiddleware::OPTION_SERVICE_NAME
                    => ValidateNotConfigured::class,

                    RequestValidateMiddleware::OPTION_SERVICE_OPTIONS => [
                        ValidateNotConfigured::OPTION_MESSAGE => null
                    ],
                ],

                /** <response-mutators> */
                ResponseHeadersAdd::configKey() => [
                    ResponseHeadersAdd::OPTION_HEADERS
                    => [],
                ],

                ResponseFormatJson::configKey() => [
                    ResponseFormatJson::OPTION_JSON_ENCODING_OPTIONS
                    => JSON_PRETTY_PRINT,
                ],

                ResponseDataExtractor::configKey() => [
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractPropertyGetter::class,
                    ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                        ExtractPropertyGetter::OPTION_PROPERTY_LIST => null,
                        ExtractPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],
                /** </response-mutators> */

                RepositoryUpsert::configKey() => [
                    RepositoryUpsert::OPTION_SERVICE_NAME
                    => \Reliv\PipeRat2\RepositoryDoctrine\Api\Upsert::class,

                    RepositoryUpsert::OPTION_SERVICE_OPTIONS => [
                        \Reliv\PipeRat2\RepositoryDoctrine\Api\Upsert::OPTION_ENTITY_CLASS_NAME
                        => '[--{entity-class}--]',
                    ],
                ],
            ],

            /* Use expressive to define allowed methods */
            'allowed_methods' => ['PUT'],
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

            RepositoryUpsert::configKey() => 100,
        ];
    }
}
