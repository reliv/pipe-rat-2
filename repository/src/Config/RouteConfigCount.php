<?php

namespace Reliv\PipeRat2\Repository\Config;

use Reliv\PipeRat2\Config\RouteConfig;
use Reliv\PipeRat2\Config\RouteConfigAbstract;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteConfigCount extends RouteConfigAbstract implements RouteConfig
{
    protected static $defaultConfig
        = [
            /* Use standard route names for client simplicity */
            'name' => '{{root}}.{{resource-name}}.count',

            /* Use standard route paths for client simplicity */
            'path' => '{{root}}/{{resource-name}}/count',

            /* Wire each API independently */
            'middleware' => [
                'response-header-mutator' => 'AddResponseHeaders',
                'response-format-mutator' => 'JsonResponseFormat',
                'data-extractor-mutator' => ResponseExtractorMiddlware::class,
                'data-body-parser' => BodyParamsMiddleware::class,
                'acl' => AclMiddleware::class,
                'request-attribute-where' => RequestFormatMiddlware::class,
                'data-validate' => InputValidateMiddleware::class,
                'controller' => RepositoryMiddleware::class,
            ],

            /* Use route to find options at runtime */
            'options' => [
                AclMiddleware::class => ['some-option' => 'some-value'],
                RequestFormatMiddlware::class => ['some-option' => 'some-value'],
                AclMiddleware::class => ['some-option' => 'some-value'],
                RepositoryMiddleware::class => [
                    'some-entity' => 'some-entity',
                    'some-hydrator' => 'some-hydrator'
                ],
                ResponseExtractorMiddlware::class => ['some-option' => 'some-value'],
            ],

            /* Use expressive to define allowed methods */
            'allowed_methods' => \Zend\Expressive\Router\Route::POST,
        ];

    protected static $defaultPriority = [
        'response-header-mutator' => 100,
        'response-format-mutator' => 200,
        'data-extractor-mutator' => 300,
        'data-body-parser' => 400,
        'acl' => 500,
        'request-attribute-where' => 600,
        'data-validate' => 700,
        'controller' => 800,
    ];
}
