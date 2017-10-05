<?php

namespace Reliv\PipeRat2\Core;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetOptionsExpressiveRoute;
use Reliv\PipeRat2\Core\Api\GetQueryParam;
use Reliv\PipeRat2\Core\Api\GetQueryParamBasic;

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
