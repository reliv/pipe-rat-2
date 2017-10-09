<?php

namespace Reliv\PipeRat2\Core\Config;

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class RouteConfigAbstract
{
    /**
     * @param string $resourceName
     * @param array  $params
     * @param array  $configOverride
     * @param array  $prioritiesOverride
     *
     * @return array
     */
    public static function get(
        string $resourceName,
        array $params,
        array $configOverride = [],
        array $prioritiesOverride = []
    ): array
    {
        $params = self::prepareParams(
            $resourceName,
            $params
        );

        self::assertHasRequiredParams($params);

        $defaultConfig = self::defaultConfig();

        $name = self::getValue(
            $configOverride,
            'name',
            $defaultConfig['name']
        );

        $path = self::getValue(
            $configOverride,
            'path',
            $defaultConfig['path']
        );

        $allowedMethods = self::getValue(
            $configOverride,
            'allowed_methods',
            $defaultConfig['allowed_methods']
        );

        $middlewareServices = self::getValue(
            $configOverride,
            'middleware',
            $defaultConfig['middleware']
        );

        $options = self::getValue(
            $configOverride,
            'options',
            $defaultConfig['options']
        );

        $config = [
            'name' => $name,
            'path' => $path,
            'middleware' => [],
            'options' => [],
            'allowed_methods' => $allowedMethods,
        ];

        $optionsProvider = new ArrayProvider($options);
        $optionsProviderDefault = new ArrayProvider($defaultConfig['options']);
        $aggregator = new ConfigAggregator([$optionsProvider, $optionsProviderDefault]);
        $config['options'] = $aggregator->getMergedConfig();

        $middlewareProvider = new ArrayProvider($middlewareServices);
        $middlewareProviderDefault = new ArrayProvider($defaultConfig['middleware']);
        $aggregator = new ConfigAggregator([$middlewareProvider, $middlewareProviderDefault]);
        $middlewareServices = $aggregator->getMergedConfig();

        $defaultPriorities = self::defaultPriorities();
        $priorityProvider = new ArrayProvider($prioritiesOverride);
        $priorityProviderDefault = new ArrayProvider($defaultPriorities);
        $aggregator = new ConfigAggregator([$priorityProvider, $priorityProviderDefault]);
        $priorities = $aggregator->getMergedConfig();

        $queue = new \SplPriorityQueue();

        $index = 0;
        foreach ($middlewareServices as $key => $middlewareService) {
            $priority = (array_key_exists($key, $priorities)) ? (int)$priorities[$key] : $index;
            $queue->insert($key, $priority);
            $index++;
        }

        foreach ($queue as $key) {
            $config['middleware'][$key] = $middlewareServices[$key];
        }

        $config = self::parseArrayParams(
            $config,
            $params
        );

        $config['name'] = self::prepareName($name);

        return $config;
    }

    /**
     * @return array
     */
    protected static function requiredParams(): array
    {
        return [
            'resource-name',
        ];
    }

    /**
     * @return array
     */
    protected static function defaultParams(): array
    {
        return [
            'root-path' => RouteRoot::get(),
        ];
    }

    /**
     * @return array
     */
    protected static function defaultConfig(): array
    {
        return [
            'name' => '[--{root-path}--].[--{resource-name}--]',
            'path' => '[--{root-path}--]/[--{resource-name}--]',
            'middleware' => [],
            'options' => [],
            'allowed_methods' => ['GET'],
        ];
    }

    /**
     * @return array
     */
    protected static function defaultPriorities(): array
    {
        return [];
    }

    protected static function assertHasRequiredParams($params)
    {
        foreach (self::requiredParams() as $requiredParam) {
            if (!array_key_exists($requiredParam, $params)) {
                throw new \Exception(
                    'Required param is missing: ' . $requiredParam
                    . '  in: ' . var_export($params, true)
                );
            }
        }
    }

    /**
     * @param string $resourceName
     * @param array  $params
     *
     * @return array
     */
    protected function prepareParams(
        string $resourceName,
        array $params
    ) {
        $params['resourceName'] = $resourceName;

        $params = array_merge(self::defaultParams(), $params);

        return $params;
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     */
    protected static function prepareName(
        string $name
    ) {
        // CamelCase to dash-separated
        $name = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
        $name = str_replace('/', '.', $name);
        $name = ltrim($name, '.');

        return $name;
    }

    /**
     * @param array $config
     * @param array $params
     *
     * @return array
     */
    protected static function parseArrayParams(
        array $config,
        array $params
    ) {
        if (!is_array($config)) {
            return $config;
        }

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $config[$key] = self::parseArrayParams(
                    $value,
                    $params
                );

                continue;
            }

            if (is_string($value)) {
                $config[$key] = self::parseValue(
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
    protected static function parseValue(
        string $value,
        array $params
    ):string
    {
        foreach ($params as $key => $param) {
            $value = str_replace('[--{' . $key . '}--]', $param, $value);
        }

        return $value;
    }

    /**
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected static function getValue(
        array $array,
        string $key,
        $default
    ) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }
}
