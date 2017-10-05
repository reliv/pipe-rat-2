<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Container\ContainerInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServiceFromConfigOptionsBasic implements GetServiceFromConfigOptions
{
    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var GetOptions
     */
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
     * @param array  $options
     * @param string $serviceInterfaceClass
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        string $serviceInterfaceClass
    ) {
        $serviceName = Options::get(
            $options,
            self::SERVICE_NAME
        );

        if (empty($serviceName)) {
            throw new \Exception(
                "Service name option does not exist with service key: " . self::SERVICE_NAME
                . " in class: " . get_class($this)
            );
        }

        if (!$this->serviceContainer->has($serviceName)) {
            throw new \Exception(
                "Service does not exist with service key: " . self::SERVICE_NAME
                . " in class: " . get_class($this)
            );
        }

        $service = $this->serviceContainer->get($serviceName);

        if (!$service instanceof $serviceInterfaceClass) {
            throw new \Exception(
                "Service must be of type: " . $serviceInterfaceClass
                . " with service key: " . self::SERVICE_NAME
                . " in class: " . get_class($this)
            );
        }

        return $service;
    }
}
