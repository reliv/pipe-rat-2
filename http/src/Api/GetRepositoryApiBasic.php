<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRepositoryApiBasic implements GetRepositoryApi
{
    protected $serviceContainer;

    protected $getOptions;

    /**
     * @param ContainerInterface $serviceContainer
     * @param GetOptions         $getOptions
     */
    public function __construct(
        $serviceContainer,
        GetOptions $getOptions
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->getOptions = $getOptions;
    }

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
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            $middlewareServiceName
        );

        if (!array_key_exists(Options::REPOSITORY_API, $options)) {
            throw new \Exception("Repository API service name option does not exist");
        }

        $repositoryServiceName = $options[Options::REPOSITORY_API];

        if (!$this->serviceContainer->has($repositoryServiceName)) {
            throw new \Exception("Repository API service does not exist: " . $repositoryServiceName);
        }

        $repositoryService = $this->serviceContainer->get($repositoryServiceName);

        if (!$repositoryService instanceof $repositoryInterfaceClass) {
            throw new \Exception("Repository API service  be of type: " . $repositoryInterfaceClass);
        }

        return $repositoryService;
    }
}
