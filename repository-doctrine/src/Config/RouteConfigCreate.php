<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Api\IsAllowedNone;
use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ResponseDataExtractor;
use Reliv\PipeRat2\Repository\Http\RepositoryCount;
use Reliv\PipeRat2\Repository\Http\RepositoryCreate;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeFieldsUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhereUrlEncodedFilters;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigCreate extends RouteConfigAbstract implements RouteConfig
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
            'name' => '[--{root-path}--].[--{resource-name}--].create',

            /* Use standard route paths for client simplicity */
            'path' => '[--{root-path}--]/[--{resource-name}--]',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
                RequestFormatJson::configKey()
                => RequestFormatJson::class,

                RequestAclMiddleware::configKey()
                => RequestAclMiddleware::class,

                RequestAttributeFieldsUrlEncodedFilters::configKey()
                => RequestAttributeFieldsUrlEncodedFilters::class,

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

                RequestAttributeFieldsUrlEncodedFilters::configKey() => [],

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
                    => \Reliv\PipeRat2\RepositoryDoctrine\Api\Create::class,

                    RepositoryCreate::OPTION_SERVICE_OPTIONS => [
                        \Reliv\PipeRat2\RepositoryDoctrine\Api\Create::OPTION_ENTITY_CLASS_NAME
                        => '[--{entity-class}--]',
                    ],
                ],
            ],

            /* Use expressive to define allowed methods */
            'allowed_methods' => ['POST'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [
            RequestFormatJson::configKey() => 700,
            RequestAclMiddleware::configKey() => 600,
            RequestAttributeFieldsUrlEncodedFilters::configKey() => 500,

            /** <response-mutators> */
            ResponseHeadersAdd::configKey() => 400,
            ResponseFormatJson::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */

            RepositoryCreate::configKey() => 100,
        ];
    }
}
