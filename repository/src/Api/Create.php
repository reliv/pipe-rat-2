<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Create
{
    /**
     * @param object|array $data
     * @param array        $options
     *
     * @return object|null $data
     */
    public function __invoke(
        $data,
        array $options = []
    );
}
