<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetOptionsExpressiveRoute implements GetOptions
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $serviceName
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $serviceName
    ): array
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if (empty($routeResult)) {
            throw new \Exception(
                'Options are only able to be processed by a routed expressive middleware'
            );
        }

        $route = $routeResult->getMatchedRoute();

        $optionsAll = $route->getOptions();

        if (!array_key_exists($serviceName, $optionsAll)) {
            return [];
        }

        if (!is_array($optionsAll[$serviceName])) {
            throw new \Exception(
                'Options must be array for service name: ' . $serviceName
                . ' got: ' . json_encode($optionsAll[$serviceName], 0, 5)
            );
        }

        return $optionsAll[$serviceName];
    }
}
