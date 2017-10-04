<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetOptions
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $serviceName
     *
     * @return Options
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $serviceName
    ): Options;
}
