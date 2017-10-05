<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

use Psr\Container\ContainerInterface;

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
        $serviceContainer
    ) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param array $options
     *
     * @return Hydrate
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):Hydrate
    {
        if (!array_key_exists(Options::HYDRATOR_SERVICE_NAME, $options)) {
            throw new \Exception("Hydrator service name not found in options: " . json_encode($options, 0, 5));
        }

        $hydratorServiceName = $options[Options::HYDRATOR_SERVICE_NAME];

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
