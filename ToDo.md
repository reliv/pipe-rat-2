ToDo
====

- Should there be a fields list option for the request used by the hydrators?
- Create JS services
- Check old piprat error formats, see what was missed
- Convert to API service style:
    - RequestAttribute
    - ResponseHeaders

```
ResponseFormat::configKey()
=> ResponseFormat::class,

ResponseFormat::configKey() => [
    ResponseFormat::OPTION_SERVICE_NAME 
    => WithFormattedResponseJson::class,
    
    ResponseFormat::OPTION_SERVICE_OPTIONS => [],
],

ResponseFormat::configKey() => 300,











```
