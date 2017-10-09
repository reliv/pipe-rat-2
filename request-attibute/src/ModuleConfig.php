<?php

namespace Reliv\PipeRat2\RequestAttribute;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeFieldsUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeLimitUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeOrderUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeSkipUrlEncodedFilters;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhereUrlEncodedFilters;

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
                    RequestAttributeFieldsUrlEncodedFilters::class => [
                        'class' => RequestAttributeFieldsUrlEncodedFilters::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeLimitUrlEncodedFilters::class => [
                        'class' => RequestAttributeLimitUrlEncodedFilters::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeOrderUrlEncodedFilters::class => [
                        'class' => RequestAttributeOrderUrlEncodedFilters::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeSkipUrlEncodedFilters::class => [
                        'class' => RequestAttributeSkipUrlEncodedFilters::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                    RequestAttributeWhereUrlEncodedFilters::class => [
                        'class' => RequestAttributeWhereUrlEncodedFilters::class,
                        'arguments' => [
                            GetOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
