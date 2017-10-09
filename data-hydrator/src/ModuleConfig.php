<?php

namespace Reliv\PipeRat2\DataHydrator;

use Reliv\PipeRat2\DataHydrator\Api\GetHydrator;
use Reliv\PipeRat2\DataHydrator\Api\GetHydratorBasicFactory;
use Reliv\PipeRat2\DataHydrator\Api\Hydrate;
use Reliv\PipeRat2\DataHydrator\Api\HydratePropertySetter;

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
                    GetHydrator::class => [
                        'factory' => GetHydratorBasicFactory::class,
                    ],
                    Hydrate::class => [
                        'class' => HydratePropertySetter::class,
                    ],
                    HydratePropertySetter::class => [],
                ],
            ],
        ];
    }
}
