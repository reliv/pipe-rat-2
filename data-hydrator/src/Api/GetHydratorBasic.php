<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

use Psr\Container\ContainerInterface;
use Reliv\PipeRat2\Core\Json;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetHydratorBasic implements GetHydrator
{
    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(
        ContainerInterface $serviceContainer
    ) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param array $options
     *
     * @return Hydrate
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        array $options
    ): Hydrate {
        if (!array_key_exists(GetHydrator::OPTION_DATA_HYDRATE_API, $options)) {
            throw new \Exception("Hydrator service name not found in options: " . Json::encode($options, 0, 5));
        }

        $hydratorServiceName = $options[GetHydrator::OPTION_DATA_HYDRATE_API];

        if (!$this->serviceContainer->has($hydratorServiceName)) {
            throw new \Exception("Hydrator service does not exist: " . $hydratorServiceName);
        }

        $hydratorService = $this->serviceContainer->get($hydratorServiceName);

        if (!$hydratorService instanceof Hydrate) {
            throw new \Exception("Hydrator service must be of type: " . Hydrate::class);
        }

        return $hydratorService;
    }
}
