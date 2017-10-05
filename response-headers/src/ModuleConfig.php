<?php

namespace Reliv\PipeRat2\ResponseHeaders;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersAdd;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersCacheMaxAge;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeadersExpires;

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
                    ResponseHeadersAdd::class => [
                        'arguments' => [
                            GetOptions::class
                        ],
                    ],
                    ResponseHeadersCacheMaxAge::class => [
                        'arguments' => [
                            GetOptions::class
                        ],
                    ],
                    ResponseHeadersExpires::class => [
                        'arguments' => [
                            GetOptions::class
                        ],
                    ],
                ],
            ],
        ];
    }
}
