ToDo
====

- Should there be a fields list option for the request used by the hydrators?
- Create JS services
- Check old piprat error formats, see what was missed
- Convert to API service style:
    - RequestAttribute
    - ResponseHeaders
- Build standard return formats

```
RequestAttributes::configKey()
=> RequestAttributes::class,

RequestAttributes::configKey() => [
    ResponseHeaders::OPTION_SERVICE_NAME
    => WithResponseHeadersAdded::class,

    ResponseHeaders::OPTION_SERVICE_OPTIONS => [
        WithResponseHeadersAdded::OPTION_HEADERS => []
    ],
],


```
