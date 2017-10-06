<?php

namespace Reliv\PipeRat2\Core\Http;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface MiddlewareWithConfigKey
{
    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string;
}
