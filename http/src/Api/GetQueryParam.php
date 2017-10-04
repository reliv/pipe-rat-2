<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetQueryParam
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
    );
}
