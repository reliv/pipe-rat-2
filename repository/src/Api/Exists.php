<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Exists
{
    /**
     * @param int|string $id
     * @param array      $options
     *
     * @return bool
     */
    public function __invoke(
        $id,
        array $options = []
    ): bool;
}
