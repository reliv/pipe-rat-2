<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Upsert
{
    /**
     * @param array $data
     * @param array $options
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $data,
        array $options = []
    );
}
