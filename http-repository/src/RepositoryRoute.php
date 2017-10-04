<?php

namespace Reliv\PipeRat2\HttpRepository;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryRoute
{
    /**
     * @var array
     */
    protected static $defaultPathConfig
        = [
            'count' => [
                'httpVerb' => 'GET',
                'path' => '/count',
            ],
            'create' => [
                'httpVerb' => 'POST',
                'path' => '',
            ],
            'deleteById' => [
                'httpVerb' => 'DELETE',
                'path' => '/{id}',
            ],
            'exists' => [
                'httpVerb' => 'GET',
                'path' => '/{id}/exists',
            ],
            'find' => [
                'httpVerb' => 'GET',
                'path' => '',
            ],
            'findById' => [
                'httpVerb' => 'GET',
                'path' => '/{id}',
            ],
            'findOne' => [
                'httpVerb' => 'GET',
                'path' => '/findOne',
            ],
            'updateProperties' => [
                'httpVerb' => 'PUT',
                'path' => '/{id}',
            ],
            'upsert' => [
                'httpVerb' => 'PUT',
                'path' => '',
            ],
        ];

    protected static $instance;

    /**
     * @param string     $root
     * @param array|null $pathConfig
     *
     * @return RepositoryRoute
     */
    public static function buildInstance(
        $root = '/',
        $pathConfig = null
    ) {
        if (self::$instance) {
            return self::$instance;
        }

        if ($pathConfig === null) {
            $pathConfig = self::$defaultPathConfig;
        }

        self::$instance = new RepositoryRoute(
            $root,
            $pathConfig
        );

        return self::$instance;
    }

    /**
     * @return RepositoryRoute
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            throw new \Exception("Instance not built");
        }

        return self::$instance;
    }

    protected $root = '/';
    protected $pathConfig = [];

    /**
     * @param string $root
     * @param array  $pathConfig
     */
    protected function __construct(
        string $root,
        array $pathConfig
    ) {
        $this->root = $root;
        $this->pathConfig = $pathConfig;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param $pathName
     *
     * @return string|null
     */
    public function getPath($pathName)
    {
        return $this->getConfigValue($pathName, 'path');
    }

    /**
     * @param $pathName
     *
     * @return string|null
     */
    public function getHttpVerb($pathName)
    {
        return $this->getConfigValue($pathName, 'httpVerb');
    }

    /**
     * @param $pathName
     * @param $key
     *
     * @return mixed|null
     */
    public function getConfigValue($pathName, $key)
    {
        if (!array_key_exists($pathName, $this->pathConfig)) {
            // @todo might throw
            return null;
        }

        if (!is_array($this->pathConfig[$pathName])) {
            // @todo might throw
            return null;
        }

        if (!array_key_exists($this->pathConfig[$pathName], $key)) {
            // @todo might throw
            return null;
        }

        return $this->pathConfig[$pathName][$key];
    }
}
