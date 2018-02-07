<?php

namespace Reliv\PipeRat2\RequestFormat;

use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\RequestFormat\Api\IsValidContentType;
use Reliv\PipeRat2\RequestFormat\Api\IsValidContentTypeBasic;
use Reliv\PipeRat2\RequestFormat\Api\IsValidRequestMethod;
use Reliv\PipeRat2\RequestFormat\Api\IsValidRequestMethodBasic;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    IsValidContentType::class => [
                        'class' => IsValidContentTypeBasic::class,
                        'arguments' => [
                            ['literal' => IsValidContentTypeBasic::DEFAULT_VALID_CONTENT_TYPES]
                        ],
                    ],

                    IsValidRequestMethod::class => [
                        'class' => IsValidRequestMethodBasic::class,
                        'arguments' => [
                            ['literal' => IsValidRequestMethodBasic::DEFAULT_REQUEST_METHODS_WITH_PARSED_BODY],
                        ],
                    ],

                    WithParsedBodyJson::class => [],

                    RequestFormat::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            IsValidContentType::class,
                            IsValidRequestMethod::class,
                            BuildFailDataResponse::class,
                            ['literal' => RequestFormat::DEFAULT_VALID_CONTENT_TYPES],
                            ['literal' => RequestFormat::DEFAULT_NOT_ALLOWED_STATUS_CODE],
                            ['literal' => RequestFormat::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE],
                        ]
                    ],
                ],
            ],
        ];
    }
}
