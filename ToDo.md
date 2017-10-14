ToDo
====

- Should there be a fields list option for the request used by the hydrators?
- Create JS services
- Check old piprat error formats, see what was missed
- Convert to API service style:
    - RequestAttribute
    - ResponseHeaders
- Build standard return formats
- 

```
RequestAttributes::configKey()
=> RequestAttributes::class,

RequestAttributes::configKey() => [
    RequestAttributes::OPTION_SERVICE_NAMES => [
        WithRequestAttributeWhere::class
        => WithRequestAttributeUrlEncodedWhere::class,

        WithRequestAttributeWhereMutator::class
        => WithRequestAttributeWhereMutatorNoop::class,
    ],

    RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
        WithRequestAttributeWhere::class => [
            WithRequestAttributeUrlEncodedWhere::OPTION_ALLOW_DEEP_WHERES => true,
        ]
    ],
],

RequestAttributes::configKey() => [
    RequestAttributes::OPTION_SERVICE_NAMES => [
        WithRequestAttributeUrlEncodedFields::class
        => WithRequestAttributeUrlEncodedFields::class,
    ],
],

WithRequestAttributeWhere::class
=> WithRequestAttributeUrlEncodedWhere::class,


```
