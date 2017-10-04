<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Hydrate
{
    /**
     * @param array        $data
     * @param object|array $dataModel
     * @param array        $options
     *
     * @return object
     */
    public function __invoke(array $data, $dataModel, array $options);
}
