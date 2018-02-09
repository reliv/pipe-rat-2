Pipe Rat
========

Create REST APIs with just a few lines of Expressive config. 
This PSR7 compliant PHP library that uses Zend\Expressive Middleware config at its core.

- Allows creation of API end points using only config for Doctrine entities
- Allows configuration of common API concerns:
    - data-extractor - Getting arrays from data objects and object arrays
    - data-hydrator - Getting data from client into data objects and arrays
    - data-validate - Validating data from client
    - repository - Common repository methods
    - request-attributes - Common query params ('where', 'fields', 'limit', 'order', 'skip', etc...)
    - request-attributes-validate - Validate the params
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

            RequestAttributesValidate::configKey()
            => RequestAttributesValidate::class,

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
                        . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                        . ' in file: "{pipe-rat-2-config.source-config-file}"',
                ],
            ],

            RequestAttributes::configKey() => [
                RequestAttributes::OPTION_SERVICE_NAMES => [
                    WithRequestAttributeFields::class
                    => WithRequestAttributeUrlEncodedFields::class,

                    WithRequestAttributeAllowedFieldConfig::class
                    => WithRequestAttributeAllowedFieldConfigFromOptions::class,

                    WithRequestAttributeExtractorFieldConfig::class
                    => WithRequestAttributeExtractorFieldConfigByRequestFields::class,

                    WithRequestAttributeWhere::class
                    => WithRequestAttributeUrlEncodedWhere::class,

                    WithRequestAttributeWhereMutator::class
                    => WithRequestAttributeWhereMutatorNoop::class,

                    WithRequestAttributeOrder::class
                    => WithRequestAttributeUrlEncodedOrder::class,

                    WithRequestAttributeSkip::class
                    => WithRequestAttributeUrlEncodedSkip::class,

                    WithRequestAttributeLimit::class
                    => WithRequestAttributeUrlEncodedLimit::class,
                ],

                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                    WithRequestAttributeAllowedFieldConfig::class => [
                        WithRequestAttributeAllowedFieldConfigFromOptions::OPTION_ALLOWED_FIELDS
                        /* @todo Over-ride with YOUR FieldsConfig */
                        => [
                            FieldConfig::KEY_TYPE => FieldConfig::COLLECTION,
                            FieldConfig::KEY_PROPERTIES => [],
                            FieldConfig::KEY_INCLUDE => true,
                        ],
                    ]
                ],
            ],
            
            RequestAttributesValidate::configKey() => [
                RequestAttributesValidate::OPTION_SERVICE_NAME
                => WithRequestValidAttributesAsserts::class,
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
                ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractByType::class,
            ],
            /** </response-mutators> */

            RepositoryFind::configKey() => [
                RepositoryFind::OPTION_SERVICE_NAME
                => FindNotConfigured::class,

                RepositoryFind::OPTION_SERVICE_OPTIONS => [
                    FindNotConfigured::OPTION_MESSAGE
                    => FindNotConfigured::DEFAULT_MESSAGE
                        . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                        . ' in file: "{pipe-rat-2-config.source-config-file}"',
                ],
            ],
        ],
        
        /* Use to define allowed methods */
        'allowed_methods' => ['GET'],
    ],
],
```

