<?php

namespace Reliv\PipeRat2\Repository\Api;

use Reliv\PipeRat2\Core\Json;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetEntityClassBasic implements GetEntityClass
{
    /**
     * @param array $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):string {
        if (!array_key_exists(self::OPTION_ENTITY_CLASS_NAME, $options)) {
            throw new \Exception("Entity class not found in options: " . Json::encode($options, 0, 5));
        }

        $entityClass = $options[self::OPTION_ENTITY_CLASS_NAME];

        if (!class_exists($entityClass)) {
            throw new \Exception("Entity class does not exist: " . $entityClass);
        }

        return $entityClass;
    }
}
