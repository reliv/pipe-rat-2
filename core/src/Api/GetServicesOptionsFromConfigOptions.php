<?php

namespace Reliv\PipeRat2\Core\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetServicesOptionsFromConfigOptions
{
    const SERVICE_OPTIONS = OptionsService::SERVICE_OPTIONS;
    const OPTION_SERVICE_NAMES_OPTIONS = OptionsService::OPTION_SERVICE_NAMES_OPTIONS;

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
    ): array;
}
