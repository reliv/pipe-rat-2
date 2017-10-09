<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Http\AclMiddleware;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ResponseDataExtractor;
use Reliv\PipeRat2\Repository\Http\RepositoryCount;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhereUrlEncodedFilters;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigCount extends RouteConfigAbstract implements RouteConfig
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
            'name' => '[--{root-path}--].[--{resource-name}--].count',

            /* Use standard route paths for client simplicity */
            'path' => '[--{root-path}--]/[--{resource-name}--]/count',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
                ResponseFormatJson::configKey()
                => ResponseFormatJson::class,

                ResponseDataExtractor::configKey()
                => ResponseDataExtractor::class,

                RequestFormatJson::configKey()
                => RequestFormatJson::class,

                AclMiddleware::configKey()
                => AclMiddleware::class,

                RequestAttributeWhereUrlEncodedFilters::configKey()
                => RequestAttributeWhereUrlEncodedFilters::class,

                RepositoryCount::configKey()
                => RepositoryCount::class,
            ],

            /* Use route to find options at runtime */
            'options' => [
                /*'{config-key}' => ['{optionKey}'=>'{optionValue}'],*/
                ResponseFormatJson::configKey() => [
                    ResponseFormatJson::OPTION_JSON_ENCODING_OPTIONS => JSON_PRETTY_PRINT,
                ],

                ResponseDataExtractor::configKey() => [
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractPropertyGetter::class,
                    ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                        ExtractPropertyGetter::OPTION_PROPERTY_LIST => [],
                        ExtractPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],

                RequestFormatJson::configKey() => [
                    RequestFormatJson::OPTION_VALID_CONTENT_TYPES => ['application/json'],
                ],

                AclMiddleware::configKey() => [
                    AclMiddleware::OPTION_SERVICE_NAME
                    => IsAllowedAny::class,

                    AclMiddleware::OPTION_SERVICE_OPTIONS => [],
                ],

                RequestAttributeWhereUrlEncodedFilters::configKey() => [
                    RequestAttributeWhereUrlEncodedFilters::OPTION_ALLOW_DEEP_WHERES => false,
                ],

                RepositoryCount::configKey() => [
                    RepositoryCount::OPTION_SERVICE_NAME
                    => \Reliv\PipeRat2\RepositoryDoctrine\Api\Count::class,

                    RepositoryCount::OPTION_SERVICE_OPTIONS => [
                        \Reliv\PipeRat2\RepositoryDoctrine\Api\FindById::OPTION_ENTITY_CLASS
                        => '[--{entity-class}--]'
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
            ResponseFormatJson::configKey() => 100,
            ResponseDataExtractor::configKey() => 200,

            RequestFormatJson::configKey() => 300,
            AclMiddleware::configKey() => 400,
            RequestAttributeWhereUrlEncodedFilters::configKey() => 500,
            RepositoryCount::configKey() => 600,
        ];
    }
}
