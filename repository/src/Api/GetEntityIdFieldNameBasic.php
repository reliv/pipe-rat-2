<?php

namespace Reliv\PipeRat2\Repository\Api;

use Reliv\PipeRat2\Core\Json;

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
        if (array_key_exists(self::OPTION_ENTITY_ID_FIELD_NAME, $options)) {
            throw new \Exception("Entity ID field name not found in options: " . Json::encode($options, 0, 5));
        }

        return $options[self::OPTION_ENTITY_ID_FIELD_NAME];
    }
}
