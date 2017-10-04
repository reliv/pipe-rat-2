<?php

namespace Reliv\PipeRat2\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Options
{
    /**
     * setFromArray
     *
     * @param array $options
     *
     * @return void
     */
    public function setFromArray(array $options);

    /**
     * get
     *
     * @param string     $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * has
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);
    
    /**
     * set
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * merge
     * - New options take precedence over local options
     *
     * @param Options $options
     *
     * @return void
     */
    public function merge(Options $options);

    /**
     * getOptions
     *
     * @param string $key
     *
     * @return Options
     */
    public function getOptions($key);

    /**
     * _toArray
     *
     * @return array
     */
    public function _toArray();
}
