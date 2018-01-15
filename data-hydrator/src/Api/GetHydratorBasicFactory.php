<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetHydratorBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetHydratorBasic
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new GetHydratorBasic($serviceContainer);
    }
}
