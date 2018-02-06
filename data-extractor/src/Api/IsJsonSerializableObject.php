<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsJsonSerializableObject
{
    /**
     * @param mixed|\JsonSerializable $dataModel
     *
     * @return bool
     */
    public static function invoke($dataModel): bool
    {
        return (is_object($dataModel) && $dataModel instanceof \JsonSerializable);
    }

    /**
     * @param mixed $dataModel
     *
     * @return bool
     */
    public function __invoke($dataModel): bool
    {
        return self::invoke($dataModel);
    }
}
