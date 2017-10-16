<?php

namespace Reliv\PipeRat2\Core\Api;

use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServicesFromConfigOptionsBasic implements GetServicesFromConfigOptions
{
    protected $getServiceFromConfigOptions;

    /**
     * @param GetServiceFromConfigOptions $getServiceFromConfigOptions
     */
    public function __construct(
        GetServiceFromConfigOptions $getServiceFromConfigOptions
    ) {
        $this->getServiceFromConfigOptions = $getServiceFromConfigOptions;
    }

    /**
     * @param array  $options
     * @param string $serviceInterfaceClass
     * @param array  $defaultServiceNames
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        string $serviceInterfaceClass,
        array $defaultServiceNames = []
    ):array {
        $serviceNames = Options::get(
            $options,
            self::OPTION_SERVICE_NAMES,
            $defaultServiceNames
        );

        $services = [];

        foreach ($serviceNames as $serviceKey => $serviceName) {
            $services[$serviceKey] = $this->getServiceFromConfigOptions->__invoke(
                [self::SERVICE_NAME => $serviceName],
                $serviceInterfaceClass
            );
        }

        return $services;
    }
}
