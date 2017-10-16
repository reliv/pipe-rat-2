<?php

namespace Reliv\PipeRat2\Core\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetServicesFromConfigOptions
{
    const SERVICE_NAME = OptionsService::SERVICE_NAME;
    const OPTION_SERVICE_NAMES = OptionsService::OPTION_SERVICE_NAMES;

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
    ):array;
}
