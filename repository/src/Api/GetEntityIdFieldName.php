<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetEntityIdFieldName
{
    const OPTION_ENTITY_ID_FIELD_NAME = 'entity-id-field-name';

    /**
     * @param array $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):string;
}
