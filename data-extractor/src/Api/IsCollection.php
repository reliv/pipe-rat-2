<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsCollection
{
    /**
     * @param mixed $dataModel
     *
     * @return bool
     */
    public static function invoke($dataModel): bool
    {
        if ($dataModel instanceof \Traversable) {
            return true;
        }

        if (IsAssociativeArray::invoke($dataModel)) {
            return false;
        }

        return is_array($dataModel);
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
