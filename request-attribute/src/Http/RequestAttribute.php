<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttribute extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attribute';
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
     * @return mixed
     * @throws \Exception
     * @throws \Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute
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

        /** @var WithRequestAttribute $withRequestAttributeApi */
        $withRequestAttributeApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            WithRequestAttribute::class
        );

        $withRequestAttributeOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $request = $withRequestAttributeApi->__invoke(
            $request,
            $response,
            $withRequestAttributeOptions
        );

        return $next($request, $response);
    }
}
