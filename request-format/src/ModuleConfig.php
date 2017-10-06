<?php

namespace Reliv\PipeRat2\RequestFormat;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormatJson;

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
                    RequestFormatJson::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
