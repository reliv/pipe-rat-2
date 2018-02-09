<?php

namespace Reliv\PipeRat2\DataValueTypes;

use Reliv\PipeRat2\DataValueTypes\Service\ValueTypes;
use Reliv\PipeRat2\DataValueTypes\Service\ValueTypesBasic;

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
                    ValueTypes::class => [
                        'class' => ValueTypesBasic::class
                    ],
                ],
            ],
        ];
    }
}
