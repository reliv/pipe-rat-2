<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Create
{
    /**
     * @param mixed $data
     * @param array $options
     *
     * @return mixed $data
     */
    public function __invoke(
        $data,
        array $options = []
    );
}
