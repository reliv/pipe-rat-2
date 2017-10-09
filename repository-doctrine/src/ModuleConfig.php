<?php

namespace Reliv\PipeRat2\RepositoryDoctrine;

use Doctrine\ORM\EntityManager;
use Reliv\PipeRat2\DataHydrator\Api\GetHydrator;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;
use Reliv\PipeRat2\RepositoryDoctrine\Api\Count;
use Reliv\PipeRat2\RepositoryDoctrine\Api\Create;
use Reliv\PipeRat2\RepositoryDoctrine\Api\DeleteById;
use Reliv\PipeRat2\RepositoryDoctrine\Api\Exists;
use Reliv\PipeRat2\RepositoryDoctrine\Api\Find;
use Reliv\PipeRat2\RepositoryDoctrine\Api\FindById;
use Reliv\PipeRat2\RepositoryDoctrine\Api\FindOne;
use Reliv\PipeRat2\RepositoryDoctrine\Api\GetEntityIdFieldName;
use Reliv\PipeRat2\RepositoryDoctrine\Api\GetEntityRepository;
use Reliv\PipeRat2\RepositoryDoctrine\Api\UpdateProperties;
use Reliv\PipeRat2\RepositoryDoctrine\Api\Upsert;

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
                    Count::class => [
                        'arguments' => [
                            GetEntityClass::class,
                            EntityManager::class,
                            GetEntityRepository::class,
                        ],
                    ],
                    Create::class => [
                        'arguments' => [
                            EntityManager::class,
                            GetEntityClass::class,
                            GetHydrator::class,
                            ['literal' => Create::DEFAULT_PROPERTY_LIST],
                            ['literal' => Create::DEFAULT_DEPTH_LIMIT],
                            ['literal' => Create::DEFAULT_DATA_HYDRATE_API]
                        ],
                    ],
                    DeleteById::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                            EntityManager::class,
                        ],
                    ],
                    Exists::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                        ],
                    ],
                    Find::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                        ],
                    ],
                    FindById::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                        ],
                    ],
                    FindOne::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                        ],
                    ],
                    GetEntityIdFieldName::class => [
                        'arguments' => [
                            EntityManager::class,
                            GetEntityClass::class
                        ],
                    ],
                    GetEntityRepository::class => [
                        'arguments' => [
                            EntityManager::class,
                            GetEntityClass::class
                        ],
                    ],
                    UpdateProperties::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                            EntityManager::class,
                            GetHydrator::class,
                            ['literal' => UpdateProperties::DEFAULT_PROPERTY_LIST],
                            ['literal' => UpdateProperties::DEFAULT_DEPTH_LIMIT],
                            ['literal' => UpdateProperties::DEFAULT_DATA_HYDRATE_API]
                        ],
                    ],
                    Upsert::class => [
                        'arguments' => [
                            GetEntityRepository::class,
                            GetEntityClass::class,
                            GetEntityIdFieldName::class,
                            EntityManager::class,
                            GetHydrator::class,
                            ['literal' => Upsert::DEFAULT_PROPERTY_LIST],
                            ['literal' => Upsert::DEFAULT_DEPTH_LIMIT],
                            ['literal' => Upsert::DEFAULT_DATA_HYDRATE_API],
                        ],
                    ],
                ],
            ],
        ];
    }
}
