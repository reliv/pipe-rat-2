<?php

namespace Reliv\PipeRat2\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Http\Api\GetRepositoryApi;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Count
{
    protected $getRepositoryApi;
    protected $middlewareServiceName;
    protected $repositoryInterfaceClass;

    /**
     * @param GetRepositoryApi $getRepositoryApi
     * @param string           $middlewareServiceName
     * @param string           $repositoryInterfaceClass
     */
    public function __construct(
        GetRepositoryApi $getRepositoryApi,
        string $middlewareServiceName = self::class,
        string $repositoryInterfaceClass = \Reliv\PipeRat2\Repository\Api\Count::class
    ) {
        $this->getRepositoryApi = $getRepositoryApi;
        $this->middlewareServiceName = $middlewareServiceName;
        $this->repositoryInterfaceClass = $repositoryInterfaceClass;
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
        /** @var \Reliv\PipeRat2\Repository\Api\Count $repository */
        $repository = $this->getRepositoryApi->__invoke(
            $request,
            $this->middlewareServiceName,
            $this->repositoryInterfaceClass
        );

        // @TODO GetWhere

        $repository->__invoke();
    }
}
