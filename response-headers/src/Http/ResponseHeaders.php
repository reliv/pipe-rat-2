<?php

namespace Reliv\PipeRat2\ResponseHeaders\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeaders;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseHeaders extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-headers';
    }

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
    ) {
        parent::__construct(
            $getOptions,
            $getServiceFromConfigOptions,
            $getServiceOptionsFromConfigOptions
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface
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

        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        /** @var WithResponseHeaders $withResponseHeadersApi */
        $withResponseHeadersApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            WithResponseHeaders::class
        );

        $withResponseHeadersOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        return $withResponseHeadersApi->__invoke(
            $request,
            $response,
            $withResponseHeadersOptions
        );
    }
}
