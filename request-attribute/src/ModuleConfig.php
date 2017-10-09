<?php

namespace Reliv\PipeRat2\RequestAttribute;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersFields;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersLimit;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersOrder;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersSkip;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeUrlEncodedFiltersWhere;

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
                    RequestAttributeUrlEncodedFiltersFields::class => [
                        'class' => RequestAttributeUrlEncodedFiltersFields::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeUrlEncodedFiltersLimit::class => [
                        'class' => RequestAttributeUrlEncodedFiltersLimit::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeUrlEncodedFiltersOrder::class => [
                        'class' => RequestAttributeUrlEncodedFiltersOrder::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeUrlEncodedFiltersSkip::class => [
                        'class' => RequestAttributeUrlEncodedFiltersSkip::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeUrlEncodedFiltersWhere::class => [
                        'class' => RequestAttributeUrlEncodedFiltersWhere::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
