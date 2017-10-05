<?php

namespace Reliv\PipeRat2\Core\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetServiceFromConfigOptions
{
    const SERVICE_NAME = OptionsService::SERVICE_NAME;

    /**
     * @param array  $options
     * @param string $serviceInterfaceClass Expected Interface
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        string $serviceInterfaceClass
    );
}
