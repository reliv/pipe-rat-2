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
     * @param array  $configOverride
     * @param array  $prioritiesOverride
     *
     * @return array
     */
    public static function get(
        string $resourceName,
        array $configOverride,
        array $prioritiesOverride
    ): array
    {
        $defaultConfig = self::defaultConfig();
            
        $name = self::getValue(
            $configOverride,
            'name',
            $defaultConfig['name']
        );
        $name = self::buildName($name, $resourceName);

        $path = self::getValue(
            $configOverride,
            'path',
            $defaultConfig['path']
        );
        $path = self::buildName($path, $resourceName);

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

        return $config;
    }

    /**
     * @param array  $array
     * @param string $key
     * @param mixed $default
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

    /**
     * @param string $name
     * @param string $resourceName
     *
     * @return string
     */
    protected static function buildName(
        string $name,
        string $resourceName
    ) {
        $name = str_replace('{{root}}', RouteRoot::get(), $name);
        $name = str_replace('{{resource-name}}', $resourceName, $name);
        $name = str_replace('/', '.', $name);
        $name = ltrim($name, '.');

        return $name;
    }

    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return string
     */
    protected static function buildPath(
        string $path,
        string $resourceName
    ) {
        $path = str_replace('{{root}}', RouteRoot::get(), $path);
        $path = str_replace('{{resource-name}}', $resourceName, $path);

        return $path;
    }
    
    protected static function defaultConfig(): array 
    {
        return [
            'name' => '{{root}}.{{resource-name}}',
            'path' => '{{root}}/{{resource-name}}',
            'middleware' => [],
            'options' => [],
            'allowed_methods' => ['GET'],
        ];
    }

    protected static function defaultPriorities(): array
    {
        return [];
    }
}
