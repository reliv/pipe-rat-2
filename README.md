Pipe Rat
========

Create REST APIs with just a few lines of Expressive config. 
This PSR7 compliant PHP library that uses Zend\Expressive Middleware at its core.

## Concept ##

- Remove unneeded complexity. Take the simplicity of pipe-rat to the next level
- Use standard expressive config format
- Split repository into discrete parts (separate concerns and improve security) 

- Config example:
    
```php
'routes' => [
    /* might key on path for speed */
    '/' . Api::ROOT . '/some-resource/' . RepositoryMiddleware::ROUTE_FIND => [
        /* Use standard route names for client simplicity */
        'name' => Api::ROOT . '.some-resource.' . RepositoryMiddleware::NAME_FIND,
        
        /* Use standard route paths for client simplicity */
        'path' => '/' . Api::ROOT . '/some-resource/' . RepositoryMiddleware::ROUTE_FIND,
        
        /* Wire each API independently */
        'middleware' => [
            OptionsMiddleware::class => OptionsMiddleware::class,
            BodyParamsMiddleware::class => BodyParamsMiddleware::class,
            AclMiddleware::class => AclMiddleware::class,
            RequestFormatMiddlware::class => RequestFormatMiddlware::class,
            InputValidateMiddleware::class => InputValidateMiddleware::class,
            RepositoryMiddleware::class => RepositoryMiddleware::class,
            ResponseExtractorMiddlware::class => ResponseExtractorMiddlware::class;
        ],
        
        /* Use route to find options at runtime */
        'options' => [
            AclMiddleware::class => [
                'some-option' => 'some-value'
            ],
            RequestFormatMiddlware::class => [
                'some-option' => 'some-value'
            ],
            AclMiddleware::class => [
                'some-option' => 'some-value'
            ],
            RepositoryMiddleware::class => [
                'some-entity' => 'some-entity',
                'some-hydrator' => 'some-hydrator'
            ],
            ResponseExtractorMiddlware::class => [
                'some-option' => 'some-value'
            ],
        ],
        
        /* Use expressive to define allowed methods */
        'allowed_methods' => Zend\Expressive\Router\Route::POST,
    ],
],
```

