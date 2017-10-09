<?php

namespace Reliv\PipeRat2\DataValidate;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\DataValidate\Api\Validate;
use Reliv\PipeRat2\DataValidate\Api\ValidateNoop;
use Reliv\PipeRat2\DataValidate\Api\ValidateNotConfigured;
use Reliv\PipeRat2\DataValidate\Api\ValidateZfInputFilter;
use Reliv\PipeRat2\DataValidate\Api\ValidateZfInputFilterFactory;
use Reliv\PipeRat2\DataValidate\Http\RequestValidateMiddleware;

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
                    Validate::class => [
                        'class' => ValidateZfInputFilter::class,
                        'factory' => ValidateZfInputFilterFactory::class,
                    ],
                    ValidateNoop::class => [
                        ['literal' => null]
                    ],
                    ValidateNotConfigured::class => [
                        ['literal' => null]
                    ],
                    ValidateZfInputFilter::class => [
                        'factory' => ValidateZfInputFilterFactory::class,
                    ],

                    RequestValidateMiddleware::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => 400]
                        ],
                    ]
                ],
            ],
        ];
    }
}
