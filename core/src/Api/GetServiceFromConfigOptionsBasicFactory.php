<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServiceFromConfigOptionsBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetServiceFromConfigOptionsBasic
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new GetServiceFromConfigOptionsBasic(
            $serviceContainer,
            $serviceContainer->get(GetOptions::class)
        );
    }
}
