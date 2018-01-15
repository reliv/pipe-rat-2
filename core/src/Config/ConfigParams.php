<?php

namespace Reliv\PipeRat2\Core\Config;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ConfigParams
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function defaultParams(): array
    {
        return [
            'root-path' => RouteRoot::get(),
            'source-config-file' => 'source-config-file not set in: ' . static::class,
        ];
    }

    /**
     * @param array $params
     * @param array $config
     *
     * @return array
     * @throws \Exception
     */
    public static function build(
        array $params,
        array $config
    ):array {
        $params = array_merge(static::defaultParams(), $params);

        return static::parse($config, $params);
    }

    /**
     * @param array $config
     * @param array $params
     *
     * @return array
     */
    public static function parse(
        array $config,
        array $params
    ) {
        if (!is_array($config)) {
            return $config;
        }

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $config[$key] = static::parse(
                    $value,
                    $params
                );

                continue;
            }

            if (is_string($value)) {
                $config[$key] = static::parseValue(
                    $value,
                    $params
                );
            }
        }

        return $config;
    }

    /**
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    public static function parseValue(
        string $value,
        array $params
    ):string {
        foreach ($params as $key => $param) {
            $value = str_replace('{pipe-rat-2-config.' . $key . '}', $param, $value);
        }

        return $value;
    }
}
