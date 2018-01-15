Pipe Rat
========

Create REST APIs with just a few lines of Expressive config. 
This PSR7 compliant PHP library that uses Zend\Expressive Middleware config at its core.

- Allows creation of API end points using only config for doctrine entities
- Allows configuration of common API concerns:
    - data-extractor - Getting arrays from data objects abd object arrays
    - data-hydrator - Getting data from client into data objects and arrays
    - data-validate - Validating data form client
    - repository - General repository methods
    - request-attributes - Common query params for 'where', 'fields', 'limit', 'order', 'skip'
    - request-format - Data format parsing the request data
    - response-format - Data format for response (JSON, etc...)
    - response-headers - Common headers for response (caching etc...)

## Config example ##
    
```php
'routes' => [
    /* Put your path here */
    'my.thing.find' => [
        /* Use standard route names for client simplicity */
        'name' => 'my.thing.find',
        
        /* Use standard route paths for client simplicity */
        'path' => 'my/thing',
        
        /* Wire each API independently */
        'middleware' => [
            RequestFormat::configKey()
            => RequestFormat::class,

            RequestAcl::configKey()
            => RequestAcl::class,

            RequestAttributes::configKey()
            => RequestAttributes::class,

            /** <response-mutators> */
            ResponseHeaders::configKey()
            => ResponseHeaders::class,

            ResponseFormat::configKey()
            => ResponseFormat::class,

            ResponseDataExtractor::configKey()
            => ResponseDataExtractor::class,
            /** </response-mutators> */

            RepositoryFind::configKey()
            => RepositoryFind::class,
        ],
        
        /* Use route to find options at runtime */
        'options' => [
            RequestFormat::configKey() => [
                RequestFormat::OPTION_SERVICE_NAME
                => WithParsedBodyJson::class,

                RequestFormat::OPTION_SERVICE_OPTIONS => [],
            ],

            RequestAcl::configKey() => [
                RequestAcl::OPTION_SERVICE_NAME
                => IsAllowedNotConfigured::class,

                RequestAcl::OPTION_SERVICE_OPTIONS => [
                    IsAllowedNotConfigured::OPTION_MESSAGE
                    => IsAllowedNotConfigured::DEFAULT_MESSAGE
                        . ' for pipe-rat-2 resource: "thing"'
                        . ' in file: __FILE__',
                ],
            ],

            RequestAttributes::configKey() => [
                RequestAttributes::OPTION_SERVICE_NAMES => [
                    WithRequestAttributeWhere::class
                    => WithRequestAttributeUrlEncodedWhere::class,

                    WithRequestAttributeWhereMutator::class
                    => WithRequestAttributeWhereMutatorNoop::class,

                    WithRequestAttributeFields::class
                    => WithRequestAttributeUrlEncodedFields::class,

                    WithRequestAttributeOrder::class
                    => WithRequestAttributeUrlEncodedOrder::class,

                    WithRequestAttributeSkip::class
                    => WithRequestAttributeUrlEncodedSkip::class,

                    WithRequestAttributeLimit::class
                    => WithRequestAttributeUrlEncodedLimit::class,
                ],

                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                    WithRequestAttributeWhere::class => [
                        WithRequestAttributeUrlEncodedWhere::OPTION_ALLOW_DEEP_WHERES => false,
                    ]
                ],
            ],

            /** <response-mutators> */
            ResponseHeaders::configKey() => [
                ResponseHeaders::OPTION_SERVICE_NAME
                => WithResponseHeadersAdded::class,

                ResponseHeaders::OPTION_SERVICE_OPTIONS => [
                    WithResponseHeadersAdded::OPTION_HEADERS => []
                ],
            ],

            ResponseFormat::configKey() => [
                ResponseFormat::OPTION_SERVICE_NAME
                => WithFormattedResponseJson::class,

                ResponseFormat::OPTION_SERVICE_OPTIONS => [],
            ],

            ResponseDataExtractor::configKey() => [
                ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractCollectionPropertyGetter::class,
                ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                    ExtractCollectionPropertyGetter::OPTION_PROPERTY_LIST => null,
                    ExtractCollectionPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                ],
            ],
            /** </response-mutators> */

            RepositoryFind::configKey() => [
                RepositoryFind::OPTION_SERVICE_NAME
                => Find::class,

                RepositoryFind::OPTION_SERVICE_OPTIONS => [
                    Find::OPTION_ENTITY_CLASS_NAME
                    => MyThingEntity::class',
                ],
            ],
        ],
        
        /* Use to define allowed methods */
        'allowed_methods' => ['GET'],
    ],
],
```

