<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetEntityIdFieldNameBasic implements GetEntityIdFieldName
{
    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @param GetEntityClass $getEntityClass
     */
    public function __construct(
        GetEntityClass $getEntityClass
    ) {
        $this->getEntityClass = $getEntityClass;
    }

    /**
     * @param array $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):string {
        if (array_key_exists(Options::ENTITY_ID_FIELD_NAME, $options)) {
            throw new \Exception("Entity ID field name not found in options: " . json_encode($options, 0, 5));
        }

        return $options[Options::ENTITY_ID_FIELD_NAME];
    }
}
