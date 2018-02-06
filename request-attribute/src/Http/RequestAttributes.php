<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServicesFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServicesOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\OptionsService;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigKey;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributes implements MiddlewareWithConfigKey
{
    const OPTION_SERVICE_NAMES = OptionsService::OPTION_SERVICE_NAMES;
    const OPTION_SERVICE_NAMES_OPTIONS = OptionsService::OPTION_SERVICE_NAMES_OPTIONS;

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
    protected $getServicesFromConfigOptions;
    protected $getServicesOptionsFromConfigOptions;
    protected $defaultServiceNames;
    protected $defaultServiceNamesOptions;

    /**
     * @param GetOptions                          $getOptions
     * @param GetServicesFromConfigOptions        $getServicesFromConfigOptions
     * @param GetServicesOptionsFromConfigOptions $getServicesOptionsFromConfigOptions
     * @param array                               $defaultServiceNames
     * @param array                               $defaultServiceNamesOptions
     */
    public function __construct(
        GetOptions $getOptions,
        GetServicesFromConfigOptions $getServicesFromConfigOptions,
        GetServicesOptionsFromConfigOptions $getServicesOptionsFromConfigOptions,
        array $defaultServiceNames = self::DEFAULT_SERVICE_NAMES,
        array $defaultServiceNamesOptions = self::DEFAULT_SERVICE_NAMES_OPTIONS
    ) {
        $this->defaultServiceNames = $defaultServiceNames;
        $this->defaultServiceNamesOptions = $defaultServiceNamesOptions;
        $this->getOptions = $getOptions;
        $this->getServicesFromConfigOptions = $getServicesFromConfigOptions;
        $this->getServicesOptionsFromConfigOptions = $getServicesOptionsFromConfigOptions;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     * @throws \Exception
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

        $withRequestAttributeApiServices = $this->getServicesFromConfigOptions->__invoke(
            $options,
            WithRequestAttribute::class,
            $this->defaultServiceNames
        );

        $withRequestAttributeServicesOptions = $this->getServicesOptionsFromConfigOptions->__invoke(
            $options
        );

        /**
         * @var string               $serviceKey
         * @var WithRequestAttribute $apiService
         */
        foreach ($withRequestAttributeApiServices as $serviceKey => $apiService) {
            $withRequestAttributeOptions = Options::get(
                $withRequestAttributeServicesOptions,
                $serviceKey,
                []
            );

            $request = $apiService->__invoke(
                $request,
                $response,
                $withRequestAttributeOptions
            );
        }

        return $next($request, $response);
    }
}
