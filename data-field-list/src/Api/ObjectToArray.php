<?php

namespace Reliv\PipeRat2\DataFieldList\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ObjectToArray
{
    /**
     * @param object $dataModel
     *
     * @return array
     */
    public function __invoke(
        $dataModel
    ): array;
}
