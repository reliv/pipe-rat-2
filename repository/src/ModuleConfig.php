<?php

namespace Reliv\PipeRat2\Repository;

use Reliv\PipeRat2\DataHydrator\Api\GetHydrator;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;
use Reliv\PipeRat2\Repository\Api\GetEntityClassBasic;
use Reliv\PipeRat2\Repository\Api\GetEntityIdFieldName;
use Reliv\PipeRat2\Repository\Api\GetEntityIdFieldNameBasic;
use Reliv\PipeRat2\Repository\Api\PopulateEntity;
use Reliv\PipeRat2\Repository\Api\PopulateEntityBasic;

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
                    GetEntityClass::class => [
                        'class' => GetEntityClassBasic::class,
                    ],
                    GetEntityIdFieldName::class => [
                        'class' => GetEntityIdFieldNameBasic::class,
                        'arguments' => [
                            '0-' => GetEntityClass::class,
                        ],
                    ],
                    PopulateEntity::class => [
                        'class' => PopulateEntityBasic::class,
                        'arguments' => [
                            '0-' => GetEntityClass::class,
                            '1-' => GetHydrator::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
