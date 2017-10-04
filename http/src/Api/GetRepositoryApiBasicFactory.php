<?php

namespace Reliv\PipeRat2\Http\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRepositoryApiBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetRepositoryApiBasic
     */
    public function __invoke(
        $serviceContainer
    ) {
        return new GetRepositoryApiBasic(
            $serviceContainer,
            GetOptions::class
        );
    }
}
