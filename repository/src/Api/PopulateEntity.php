<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface PopulateEntity
{
    /**
     * @param array  $options
     * @param array  $properties
     * @param object $entity
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        array $properties,
        $entity
    );
}
