<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UpdateProperties
{
    /**
     * @param int|string $id
     * @param array      $properties
     * @param array      $options
     *
     * @return object|null $data
     */
    public function __invoke(
        $id,
        array $properties,
        array $options = []
    );
}
