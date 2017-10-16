<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExtractNoop implements Extract
{
    /**
     * extract and return data if possible
     *
     * @param object|array $dataModel
     * @param array        $options
     *
     * @return array
     */
    public function __invoke($dataModel, array $options)
    {
        return $dataModel;
    }
}
