<?php

namespace Reliv\PipeRat2\DataFieldList\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BuildFieldList
{
    /**
     * Return an array of fields available (FieldList)
     *
     * @param array $options
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        array $options = []
    ): array;
}
