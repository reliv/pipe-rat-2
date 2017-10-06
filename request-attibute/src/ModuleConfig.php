<?php

namespace Reliv\PipeRat2\RequestAttribute;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeFields;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeLimit;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeSkip;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhere;

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
                    RequestAttributeFields::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeLimit::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeOrder::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeSkip::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeWhere::class => [
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
