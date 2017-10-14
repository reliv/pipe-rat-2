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
        WithRequestAttributeUrlEncodedWhere::class
        => WithRequestAttributeUrlEncodedWhere::class,
    ],

    RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
        WithRequestAttributeUrlEncodedWhere::class => [
            WithRequestAttributeUrlEncodedWhere::OPTION_ALLOW_DEEP_WHERES => false,
        ]
    ],
],

RequestAttributes::configKey() => [
    RequestAttributes::OPTION_SERVICE_NAMES => [
        WithRequestAttributeUrlEncodedFields::class
        => WithRequestAttributeUrlEncodedFields::class,
    ],
],


```
