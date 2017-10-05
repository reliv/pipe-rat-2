<?php

namespace Reliv\PipeRat2\Core\Api;

use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServiceOptionsFromConfigOptionsBasic implements GetServiceOptionsFromConfigOptions
{
    /**
     * @param array  $options
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ): array {
        return (array)Options::get(
            $options,
            self::SERVICE_OPTIONS,
            []
        );
    }
}
