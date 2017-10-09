<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Hydrate
{
    const OPTION_PROPERTY_LIST = 'property-list';
    const OPTION_DEPTH_LIMIT = 'property-depth-limit';

    /**
     * @param array        $data
     * @param object|array $dataModel
     * @param array        $options
     *
     * @return object|array
     */
    public function __invoke(
        array $data,
        $dataModel,
        array $options
    );
}
