<?php

namespace Reliv\PipeRat2\Core\Config;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RouteConfig
{
    /**
     * @param string $resourceName
     * @param array  $configOverride
     * @param array  $prioritiesOverride
     *
     * @return array
     */
    public static function get(
        string $resourceName,
        array $configOverride,
        array $prioritiesOverride
    ): array;
}
