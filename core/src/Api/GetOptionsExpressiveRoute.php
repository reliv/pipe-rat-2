<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Json;
use Zend\Expressive\Router\RouteResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetOptionsExpressiveRoute implements GetOptions
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $configKey
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $configKey
    ): array {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if (empty($routeResult)) {
            throw new \Exception(
                'Options are only able to be processed by a routed expressive middleware'
            );
        }

        $route = $routeResult->getMatchedRoute();

        $optionsAll = $route->getOptions();

        if (!array_key_exists($configKey, $optionsAll)) {
            return [];
        }

        if (!is_array($optionsAll[$configKey])) {
            throw new \Exception(
                'Options must be array for config key: ' . $configKey
                . ' got: ' . Json::encode($optionsAll[$configKey], 0, 5)
            );
        }

        return $optionsAll[$configKey];
    }
}
