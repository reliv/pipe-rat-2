<?php

namespace Reliv\PipeRat2\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Options
{
    /**
     * @param array  $options
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public static function get(
        array $options,
        string $key,
        $default = null
    ) {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }

    /**
     * @param array  $options
     * @param string $key
     *
     * @return bool
     */
    public static function has(
        array $options,
        string $key
    ): bool {
        return array_key_exists($key, $options);
    }
}
