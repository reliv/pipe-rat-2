<?php

namespace Reliv\PipeRat2\Http;

use Reliv\PipeRat2\Http\Api\GetOptions;
use Reliv\PipeRat2\Http\Api\GetOptionsExpressiveRoute;
use Reliv\PipeRat2\Http\Api\GetQueryParam;
use Reliv\PipeRat2\Http\Api\GetQueryParamBasic;

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
                    GetOptions::class => [
                        'class' => GetOptionsExpressiveRoute::class,
                    ],
                    GetQueryParam::class => [
                        'class' => GetQueryParamBasic::class,
                    ],
                ],
            ],
        ];
    }
}
