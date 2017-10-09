<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine;

use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigCount;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigCreate;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigDeleteById;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigExists;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigFind;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigFindById;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigFindOne;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigUpdateProperties;
use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigUpsert;
use Reliv\PipeRat2\XampleRepositoryDoctrine\Entity\XampleEntity;

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
            'doctrine' => [
                'driver' => [
                    'Reliv\PipeRat2\XampleRepositoryDoctrine' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Entity'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Reliv\PipeRat2\XampleRepositoryDoctrine'
                            => 'Reliv\PipeRat2\XampleRepositoryDoctrine'
                        ]
                    ]
                ],
                'configuration' => [
                    'orm_default' => [
                        'metadata_cache' => 'doctrine_cache',
                        'query_cache' => 'doctrine_cache',
                        'result_cache' => 'doctrine_cache',
                    ]
                ],
            ],

            'routes' => [
                'pipe-rat-2.xample.count'
                => RouteConfigCount::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.create'
                => RouteConfigCreate::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.delete'
                => RouteConfigDeleteById::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.exists'
                => RouteConfigExists::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                // NOTE: 'findeOne' MUST be defined before the 'find' route or it will conflict
                'pipe-rat-2.xample.findOne'
                => RouteConfigFindOne::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.find'
                => RouteConfigFind::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.findById'
                => RouteConfigFindById::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.update-properties'
                => RouteConfigUpdateProperties::get(
                    'xample/t',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),

                'pipe-rat-2.xample.upsert'
                => RouteConfigUpsert::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                ),
            ]
        ];
    }
}
