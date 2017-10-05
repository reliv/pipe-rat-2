<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetQueryParamBasic implements GetQueryParam
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $paramName
     * @param null                   $default
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $paramName,
        $default = null
    ) {
        $params = $request->getQueryParams();

        if (array_key_exists($paramName, $params)) {
            return $params[$paramName];
        }

        return $default;
    }
}
