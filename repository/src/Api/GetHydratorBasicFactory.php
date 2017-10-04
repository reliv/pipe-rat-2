<?php

namespace Reliv\PipeRat2\Repository\Api;

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
        $serviceContainer
    )
    {
        return new GetHydratorBasic($serviceContainer);
    }
}
