<?php

namespace Reliv\PipeRat2\ResponseFormat;

use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptType;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptTypeBasic;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattableBasic;

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
                    IsRequestValidAcceptType::class => [
                        'class' => IsRequestValidAcceptTypeBasic::class,
                        'arguments' => [
                            IsRequestValidAcceptTypeBasic::DEFAULT_ACCEPTS
                        ]
                    ],
                    IsResponseFormattable::class => [
                        'class' => IsResponseFormattableBasic::class,
                        'arguments' => [
                            IsResponseFormattableBasic::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
                        ]
                    ],
                    IsResponseFormattableBasic::class => [],
                ],
            ],
        ];
    }
}
