<?php

namespace Reliv\PipeRat2\Core\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetServiceOptionsFromConfigOptions
{
    const SERVICE_OPTIONS = OptionsService::SERVICE_OPTIONS;

    /**
     * @param array  $options
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ): array;
}
