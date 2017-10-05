<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Extract
{
    const OPTION_PROPERTY_LIST = OptionsExtract::PROPERTY_LIST;
    const OPTION_PROPERTY_DEPTH_LIMIT = OptionsExtract::PROPERTY_DEPTH_LIMIT;
    /**
     * extract and return data if possible
     *
     * @param object|array $dataModel
     * @param array        $options
     *
     * @return array
     */
    public function __invoke($dataModel, array $options);
}
