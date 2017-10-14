<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\OptionsService;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigKey;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributes implements MiddlewareWithConfigKey
{
    const OPTION_SERVICE_NAMES = 'service-names';
    const OPTION_SERVICE_NAMES_OPTIONS = 'service-names-options';

    const DEFAULT_SERVICE_NAMES = [];
    const DEFAULT_SERVICE_NAMES_OPTIONS = [];

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attributes';
    }

    protected $getOptions;
    protected $getServiceFromConfigOptions;
    protected $getServiceOptionsFromConfigOptions;
    protected $defaultServiceNames;
    protected $defaultServiceNamesOptions;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param array                              $defaultServiceNames
     * @param array                              $defaultServiceNamesOptions
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        array $defaultServiceNames = self::DEFAULT_SERVICE_NAMES,
        array $defaultServiceNamesOptions = self::DEFAULT_SERVICE_NAMES_OPTIONS
    ) {
        $this->defaultServiceNames = $defaultServiceNames;
        $this->defaultServiceNamesOptions = $defaultServiceNamesOptions;
        $this->getOptions = $getOptions;
        $this->getServiceFromConfigOptions = $getServiceFromConfigOptions;
        $this->getServiceOptionsFromConfigOptions = $getServiceOptionsFromConfigOptions;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $serviceNames = Options::get(
            $options,
            self::OPTION_SERVICE_NAMES,
            $this->defaultServiceNames
        );

        $serviceNamesOptions = Options::get(
            $options,
            self::OPTION_SERVICE_NAMES_OPTIONS,
            $this->defaultServiceNames
        );

        foreach ($serviceNames as $serviceName) {
            // @todo This can be more efficient
            $serviceOptions = [
                OptionsService::SERVICE_NAME => $serviceName,
                OptionsService::SERVICE_OPTIONS => Options::get(
                    $serviceNamesOptions,
                    $serviceName,
                    []
                )
            ];

            $withRequestAttributeApi = $this->getServiceFromConfigOptions->__invoke(
                $serviceOptions,
                WithRequestAttribute::class
            );

            $withRequestAttributeOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
                $serviceOptions
            );

            $request = $withRequestAttributeApi->__invoke(
                $request,
                $response,
                $withRequestAttributeOptions
            );
        }

        return $next($request, $response);
    }
}
