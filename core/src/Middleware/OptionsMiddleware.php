<?php

namespace Reliv\PipeRat2\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class OptionsMiddleware
{
    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $out
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $out = null
    ) {
        $request = $request->withAttribute(MiddlewareOptions::getName(), $this->options);

        return $out($request, $response);
    }
}
