<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\Core\Api\OptionsService;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\Validate;
use Reliv\PipeRat2\DataValidate\Http\RequestValidateMiddleware;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhere;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersWhere;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormatJson;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;
use Reliv\PipeRat2\XampleRepositoryDoctrine\Entity\XampleEntity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigExample extends RouteConfigAbstract implements RouteConfig
{
    protected static function defaultParams(): array
    {
        $defaultParams = parent::defaultParams();
        $defaultParams['entity-class'] = XampleEntity::class;

        return $defaultParams;
    }

    protected static function defaultConfig(): array
    {
        return [
            /* Use standard route names for client simplicity */
            'name' => '[--{root-path}--].[--{resource-name}--].example',

            /* Use standard route paths for client simplicity */
            'path' => '[--{root-path}--]/[--{resource-name}--]/example',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
                RequestFormatJson::configKey()
                => RequestFormatJson::class,

                RequestAclMiddleware::configKey()
                => RequestAclMiddleware::class,

                RequestAttributeUrlEncodedFiltersWhere::configKey()
                => RequestAttributeUrlEncodedFiltersWhere::class,

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

                RepositoryFindById::configKey()
                => RepositoryFindById::class,
            ],

            /* Use route to find options at runtime */
            'options' => [
                /*'{config-key}' => ['{optionKey}'=>'{optionValue}'],*/
                RequestFormatJson::configKey() => [
                    RequestFormatJson::OPTION_VALID_CONTENT_TYPES => ['application/json'],
                ],

                RequestAclMiddleware::configKey() => [
                    OptionsService::SERVICE_NAME
                    => IsAllowedRcmUser::class,

                    OptionsService::SERVICE_OPTIONS => [
                        IsAllowedRcmUser::OPTION_RESOURCE_ID => 'admin',
                        IsAllowedRcmUser::OPTION_PRIVILEGE => null,
                    ],
                    RequestAclMiddleware::OPTION_NOT_ALLOWED_STATUS_CODE => 401,
                    RequestAclMiddleware::OPTION_NOT_ALLOWED_STATUS_MESSAGE => 'No way man!',
                ],

                RequestAttributeUrlEncodedFiltersWhere::configKey() => [
                    RequestAttributeWhere::OPTION_ALLOW_DEEP_WHERES => false,
                ],

                RequestValidateMiddleware::configKey() => [
                    OptionsService::SERVICE_NAME
                    => Validate::class,

                    OptionsService::SERVICE_OPTIONS => [
                        Validate::OPTION_PRIMARY_MESSAGE => 'Well, that is not good!'
                    ],
                    RequestValidateMiddleware::OPTION_FAIL_STATUS_CODE => 400,
                ],

                /** <response-mutators> */
                ResponseHeadersAdd::configKey() => [
                    ResponseHeadersAdd::OPTION_HEADERS => ['header-name' => 'header-value'],
                ],

                ResponseFormatJson::configKey() => [
                    ResponseFormatJson::OPTION_JSON_ENCODING_OPTIONS => JSON_PRETTY_PRINT,
                ],

                ResponseDataExtractor::configKey() => [
                    OptionsService::SERVICE_NAME => ExtractPropertyGetter::class,
                    OptionsService::SERVICE_OPTIONS => [
                        ExtractPropertyGetter::OPTION_PROPERTY_LIST => [],
                        ExtractPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],
                /** </response-mutators> */

                RepositoryFindById::configKey() => [
                    OptionsService::SERVICE_NAME
                    => \Reliv\PipeRat2\RepositoryDoctrine\Api\FindById::class,

                    OptionsService::SERVICE_OPTIONS => [
                        \Reliv\PipeRat2\RepositoryDoctrine\Api\FindById::OPTION_ENTITY_CLASS_NAME
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
            RequestFormatJson::configKey() => 800,
            RequestAclMiddleware::configKey() => 700,
            RequestAttributeUrlEncodedFiltersWhere::configKey() => 600,
            RequestValidateMiddleware::configKey() => 500,
            /** <response-mutators> */
            ResponseHeadersAdd::configKey() => 400,
            ResponseFormatJson::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */
            RepositoryFindById::configKey() => 100,
        ];
    }
}
