<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Config;

use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Core\Config\RouteConfig;
use Reliv\PipeRat2\Core\Config\RouteConfigAbstract;
use Reliv\PipeRat2\Core\DataResponse;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\Validate;
use Reliv\PipeRat2\DataValidate\Http\RequestDataValidate;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\RepositoryDoctrine\Api\FindById;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedWhere;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersAdded;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeaders;
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
            'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.example',

            /* Use standard route paths for client simplicity */
            'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}/example',

            /* Wire each API independently */
            'middleware' => [
                /*'{config-key}' => '{service-name}',*/
                RequestFormat::configKey()
                => RequestFormat::class,

                RequestAcl::configKey()
                => RequestAcl::class,

                RequestAttributes::configKey()
                => RequestAttributes::class,

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

                RepositoryFindById::configKey()
                => RepositoryFindById::class,
            ],

            /* Use route to find options at runtime */
            'options' => [
                /*'{config-key}' => ['{optionKey}'=>'{optionValue}'],*/
                RequestFormat::configKey() => [
                    RequestFormat::OPTION_SERVICE_NAME
                    => WithParsedBodyJson::class,

                    RequestFormat::OPTION_SERVICE_OPTIONS => [],

                    RequestFormat::OPTION_VALID_CONTENT_TYPES => ['application/json'],
                    RequestFormat::OPTION_NOT_ACCEPTABLE_STATUS_CODE => 406,
                    RequestFormat::OPTION_NOT_ACCEPTABLE_STATUS_MESSAGE => "Not a JSON request",
                ],

                RequestAcl::configKey() => [
                    RequestAcl::OPTION_SERVICE_NAME
                    => IsAllowedRcmUser::class,

                    RequestAcl::OPTION_SERVICE_OPTIONS => [
                        IsAllowedRcmUser::OPTION_RESOURCE_ID => 'admin',
                        IsAllowedRcmUser::OPTION_PRIVILEGE => null,
                    ],

                    RequestAcl::OPTION_NOT_ALLOWED_STATUS_CODE => 401,
                    RequestAcl::OPTION_NOT_ALLOWED_STATUS_MESSAGE => 'No way man!',
                ],

                RequestAttributes::configKey() => [
                    RequestAttributes::OPTION_SERVICE_NAMES => [
                        WithRequestAttributeUrlEncodedWhere::class
                        => WithRequestAttributeUrlEncodedWhere::class,
                    ],

                    RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                        WithRequestAttributeUrlEncodedWhere::class => [
                            WithRequestAttributeUrlEncodedWhere::OPTION_ALLOW_DEEP_WHERES => false,
                        ]
                    ],
                ],

                RequestDataValidate::configKey() => [
                    RequestDataValidate::OPTION_SERVICE_NAME
                    => Validate::class,

                    RequestDataValidate::OPTION_SERVICE_OPTIONS => [
                        Validate::OPTION_PRIMARY_MESSAGE => 'Well, that is not good!'
                    ],
                    RequestDataValidate::OPTION_FAIL_STATUS_CODE => 400,
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
                    ResponseFormat::OPTION_SERVICE_NAME => WithFormattedResponseJson::class,
                    ResponseFormat::OPTION_SERVICE_OPTIONS => [
                        WithFormattedResponseJson::OPTION_JSON_ENCODING_OPTIONS => JSON_PRETTY_PRINT,
                        WithFormattedResponseJson::OPTION_CONTENT_TYPE => 'application/json',
                        WithFormattedResponseJson::OPTION_FORMATTABLE_RESPONSE_CLASSES => [DataResponse::class]
                    ],
                ],

                ResponseDataExtractor::configKey() => [
                    ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractPropertyGetter::class,
                    ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                        ExtractPropertyGetter::OPTION_PROPERTY_LIST => [],
                        ExtractPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                    ],
                ],
                /** </response-mutators> */

                RepositoryFindById::configKey() => [
                    RepositoryFindById::OPTION_SERVICE_NAME
                    => FindById::class,

                    RepositoryFindById::OPTION_SERVICE_OPTIONS => [
                        FindById::OPTION_ENTITY_CLASS_NAME
                        => '{pipe-rat-2-config.entity-class}',
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
            RequestFormat::configKey() => 800,
            RequestAcl::configKey() => 700,
            RequestAttributes::configKey() => 600,
            RequestDataValidate::configKey() => 500,
            /** <response-mutators> */
            ResponseHeaders::configKey() => 400,
            ResponseFormat::configKey() => 300,
            ResponseDataExtractor::configKey() => 200,
            /** </response-mutators> */
            RepositoryFindById::configKey() => 100,
        ];
    }
}
