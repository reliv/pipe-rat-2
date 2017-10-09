<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine;

use Reliv\PipeRat2\RepositoryDoctrine\Config\RouteConfigCount;
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
                            __DIR__ . '/../Entity'
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
                '[--{root-path}--].xample.count'
                => RouteConfigCount::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class
                    ],
                    []
                )
            ]
        ];
    }
}
