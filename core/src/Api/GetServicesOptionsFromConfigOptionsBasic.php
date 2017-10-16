<?php

namespace Reliv\PipeRat2\Core\Api;

use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServicesOptionsFromConfigOptionsBasic implements GetServicesOptionsFromConfigOptions
{
    protected $getServiceOptionsFromConfigOptions;

    /**
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     */
    public function __construct(
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
    ) {
        $this->getServiceOptionsFromConfigOptions = $getServiceOptionsFromConfigOptions;
    }

    /**
     * @param array $options
     * @param array $defaultServiceNames
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        array $defaultServiceNames = []
    ): array {
        $serviceNamesOptions = Options::get(
            $options,
            self::OPTION_SERVICE_NAMES_OPTIONS,
            $defaultServiceNames
        );

        $servicesOptions = [];

        foreach ($serviceNamesOptions as $serviceKey => $serviceName) {
            $servicesOptions[$serviceKey] = $this->getServiceOptionsFromConfigOptions->__invoke(
                [self::SERVICE_OPTIONS => $serviceName]
            );
        }

        return $servicesOptions;
    }
}
