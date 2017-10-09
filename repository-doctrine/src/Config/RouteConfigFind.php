<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractCollectionPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ResponseDataExtractor;
use Reliv\PipeRat2\Repository\Http\RepositoryFind;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeFieldsUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeLimitUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeOrderUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeSkipUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhereUrlEncodedFilters;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigFind extends RouteConfigAbstract implements RouteConfig
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
            'name' => '[--{root-path}--].[--{resource-name}--].find',

            /* Use standard route paths for client simplicity */
            'path' => '[--{root-path}--]/[--{resource-name}--]',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
                RequestFormatJson::configKey()
                => RequestFormatJson::class,

                RequestAclMiddleware::configKey()
                => RequestAclMiddleware::class,

                RequestAttributeWhereUrlEncodedFilters::configKey()
                => RequestAttributeWhereUrlEncodedFilters::class,

                RequestAttributeFieldsUrlEncodedFilters::configKey()
                => RequestAttributeFieldsUrlEncodedFilters::class,

                RequestAttributeOrderUrlEncodedFilters::configKey()
                => RequestAttributeOrderUrlEncodedFilters::class,

                RequestAttributeSkipUrlEncodedFilters::configKey()
                => RequestAttributeSkipUrlEncodedFilters::class,

                RequestAttributeLimitUrlEncodedFilters::configKey()
                => RequestAttributeLimitUrlEncodedFilters::class,

                /** <response-mutators> */
                ResponseHeadersAdd::configKey()
                => ResponseHeadersAdd::class,

                ResponseFormatJson::configKey()
                => ResponseFormatJson::class,

                ResponseDataExtractor::configKey()
                => ResponseDataExtractor::class,
                /** </response-mutators> */

                RepositoryFind::configKey()
                => RepositoryFind::class,
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

                RequestAttributeWhereUrlEncodedFilters::configKey() => [
                    RequestAttributeWhereUrlEncodedFilters::OPTION_ALLOW_DEEP_WHERES => false,
                ],

                RequestAttributeFieldsUrlEncodedFilters::configKey() => [],

                RequestAttributeOrderUrlEncodedFilters::configKey() => [],

                RequestAttributeSkipUrlEncodedFilters::configKey() => [],

                RequestAttributeLimitUrlEncodedFilters::configKey() => [],

                /** <response-mutators> */
                ResponseHeadersAdd::configKey() => [
                    ResponseHeadersAdd::OPTION_HEADERS
                    => [],
                ],

                ResponseFormatJson::configKey() => [
                    ResponseFormatJson::OPTION_JSON_ENCODING_OPTIONS => JSON_PRETTY_PRINT,
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
                    => \Reliv\PipeRat2\RepositoryDoctrine\Api\Find::class,

                    RepositoryFind::OPTION_SERVICE_OPTIONS => [
                        \Reliv\PipeRat2\RepositoryDoctrine\Api\Find::OPTION_ENTITY_CLASS_NAME
                        => '[--{entity-class}--]',
                    ],
                ],
            ],

            /* Use expressive to define allowed methods */
            'allowed_methods' => ['GET'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormatJson::configKey() => 1100,
            RequestAclMiddleware::configKey() => 1000,
            RequestAttributeWhereUrlEncodedFilters::configKey() => 900,
            RequestAttributeFieldsUrlEncodedFilters::configKey() => 800,
            RequestAttributeOrderUrlEncodedFilters::configKey() => 700,
            RequestAttributeSkipUrlEncodedFilters::configKey() => 600,
            RequestAttributeLimitUrlEncodedFilters::configKey() => 500,

            /** <response-mutators> */
            ResponseHeadersAdd::configKey() => 400,
            ResponseFormatJson::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryFind::configKey() => 100,
        ];
    }
}
