<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Extract
{
    /**
     * extract and return data if possible
     *
     * @param object|array $dataModel
     * @param array        $fieldConfig
     *
     * @return array|string|int|bool|null
     */
    public function __invoke(
        $dataModel,
        array $fieldConfig = []
    );
}
