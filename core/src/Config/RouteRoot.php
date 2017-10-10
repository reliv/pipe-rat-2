<?php

namespace Reliv\PipeRat2\Core\Config;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RouteRoot
{
    const DEFAULT_ROOT_PATH = '/api/resource';

    protected static $rootPath;

    protected static $isSet = false;

    /**
     * @param string $rootPath
     *
     * @return void
     * @throws \Exception
     */
    public static function bootstrap(string $rootPath = self::DEFAULT_ROOT_PATH)
    {
        if (self::$isSet) {
            throw new \Exception('Root path can only be set once on bootstrap');
        }

        self::$rootPath = $rootPath;
        self::$isSet = true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function get()
    {
        if (!self::$isSet) {
            self::bootstrap(self::DEFAULT_ROOT_PATH);
        }

        return self::$rootPath;
    }
}
