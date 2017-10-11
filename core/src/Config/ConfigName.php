<?php

namespace Reliv\PipeRat2\Core\Config;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ConfigName
{
    /**
     * @param string $name
     *
     * @return mixed|string
     */
    public static function parse(
        string $name
    ) {
        // CamelCase to dash-separated
        $name = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
        $name = str_replace('/', '.', $name);
        $name = ltrim($name, '.');

        return $name;
    }
}
