<?php

namespace Reliv\PipeRat2\ResponseHeaders;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersAdded;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersCacheMaxAge;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeaders;

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
                    WithResponseHeadersAdded::class => [
                        'arguments' => [
                            ['literal' => WithResponseHeadersAdded::DEFAULT_HEADERS],
                        ],
                    ],
                    WithResponseHeadersCacheMaxAge::class => [
                        'arguments' => [
                            ['literal' => WithResponseHeadersCacheMaxAge::DEFAULT_HTTP_METHODS],
                            ['literal' => WithResponseHeadersCacheMaxAge::DEFAULT_PRAGMA],
                            ['literal' => WithResponseHeadersCacheMaxAge::DEFAULT_MAX_AGE],
                        ],
                    ],
                    ResponseHeaders::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
