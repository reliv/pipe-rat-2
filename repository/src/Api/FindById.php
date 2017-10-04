<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindById
{
    /**
     * @param int|string $id
     * @param array      $options
     *
     * @return mixed $data
     */
    public function __invoke(
        $id,
        array $options = []
    );
}
