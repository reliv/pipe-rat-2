<?php

namespace Reliv\PipeRat2\Repository;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;
use Reliv\PipeRat2\Repository\Api\GetEntityClassBasic;
use Reliv\PipeRat2\Repository\Api\GetEntityIdFieldName;
use Reliv\PipeRat2\Repository\Api\GetEntityIdFieldNameBasic;
use Reliv\PipeRat2\Repository\Http\RepositoryCount;
use Reliv\PipeRat2\Repository\Http\RepositoryCreate;
use Reliv\PipeRat2\Repository\Http\RepositoryDeleteById;
use Reliv\PipeRat2\Repository\Http\RepositoryExists;
use Reliv\PipeRat2\Repository\Http\RepositoryFind;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\Repository\Http\RepositoryFindOne;
use Reliv\PipeRat2\Repository\Http\RepositoryUpdateProperties;
use Reliv\PipeRat2\Repository\Http\RepositoryUpsert;

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

                    RepositoryCount::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                        ],
                    ],
                    RepositoryCreate::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                        ],
                    ],
                    RepositoryDeleteById::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RepositoryDeleteById::DEFAULT_ID_PARAM],
                            ['literal' => RepositoryDeleteById::DEFAULT_BAD_REQUEST_STATUS_CODE],
                            ['literal' => RepositoryDeleteById::DEFAULT_BAD_REQUEST_MESSAGE],
                        ],
                    ],
                    RepositoryExists::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RepositoryExists::DEFAULT_ID_PARAM],
                            ['literal' => RepositoryExists::DEFAULT_BAD_REQUEST_STATUS_CODE],
                            ['literal' => RepositoryExists::DEFAULT_BAD_REQUEST_MESSAGE],
                        ],
                    ],
                    RepositoryFind::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RepositoryFind::DEFAULT_NOT_FOUND_STATUS_CODE],
                            ['literal' => RepositoryFind::DEFAULT_NOT_FOUND_MESSAGE],
                        ],
                    ],
                    RepositoryFindById::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RepositoryFindById::DEFAULT_ID_PARAM],
                            ['literal' => RepositoryFindById::DEFAULT_BAD_REQUEST_STATUS_CODE],
                            ['literal' => RepositoryFindById::DEFAULT_BAD_REQUEST_MESSAGE],
                            ['literal' => RepositoryFindById::DEFAULT_NOT_FOUND_STATUS_CODE],
                            ['literal' => RepositoryFindById::DEFAULT_NOT_FOUND_MESSAGE],
                        ],
                    ],
                    RepositoryFindOne::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RepositoryFindOne::DEFAULT_NOT_FOUND_STATUS_CODE],
                            ['literal' => RepositoryFindOne::DEFAULT_NOT_FOUND_MESSAGE],
                        ],
                    ],
                    RepositoryUpdateProperties::class => [
                        'arguments' => [
                            // @todo
                        ],
                    ],
                    RepositoryUpsert::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                        ],
                    ],
                ],
            ],
        ];
    }
}
