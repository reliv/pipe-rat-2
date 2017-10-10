<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine;

use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
use Reliv\PipeRat2\DataValidate\Api\ValidateNoop;
use Reliv\PipeRat2\DataValidate\Http\RequestValidateMiddleware;
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
                /**
                 * NOTE: The ORDER these are defined in is IMPORTANT
                 * Routing needs to match more specific routes BEFORE general/variable routes
                 */

                /**
                 * PATH: '/count'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.xample.count'
                => RouteConfigCount::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/findOne'
                 * VERB: 'GET'
                 *  */
                'pipe-rat-2.xample.find-one'
                => RouteConfigFindOne::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/{id}'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.xample.find-by-id'
                => RouteConfigFindById::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/{id}'
                 * VERB: 'PUT'
                 */
                'pipe-rat-2.xample.update-properties'
                => RouteConfigUpdateProperties::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                            RequestValidateMiddleware::configKey() => [
                                RequestValidateMiddleware::OPTION_SERVICE_NAME
                                => ValidateNoop::class,

                                RequestValidateMiddleware::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/{id}'
                 * VERB: 'DELETE'
                 */
                'pipe-rat-2.xample.delete-by-id'
                => RouteConfigDeleteById::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/{id}/exists'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.xample.exists'
                => RouteConfigExists::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.xample.find'
                => RouteConfigFind::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/'
                 * VERB: 'POST'
                 */
                'pipe-rat-2.xample.create'
                => RouteConfigCreate::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                            RequestValidateMiddleware::configKey() => [
                                RequestValidateMiddleware::OPTION_SERVICE_NAME
                                => ValidateNoop::class,

                                RequestValidateMiddleware::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ]
                    ]
                ),

                /**
                 * PATH: '/'
                 * VERB: 'PUT'
                 */
                'pipe-rat-2.xample.upsert'
                => RouteConfigUpsert::get(
                    'xample',
                    [
                        'entity-class' => XampleEntity::class,
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAclMiddleware::configKey() => [
                                RequestAclMiddleware::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAclMiddleware::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                            RequestValidateMiddleware::configKey() => [
                                RequestValidateMiddleware::OPTION_SERVICE_NAME
                                => ValidateNoop::class,

                                RequestValidateMiddleware::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ]
                    ]
                ),
            ]
        ];
    }
}
