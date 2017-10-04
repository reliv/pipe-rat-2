<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetRepositoryApi
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $middlewareServiceName
     * @param string                 $repositoryInterfaceClass
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $middlewareServiceName,
        string $repositoryInterfaceClass
    );
}
