<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAssociativeArray
{
    /**
     * @param mixed $dataModel
     *
     * @return bool
     */
    public static function invoke(
        $dataModel
    ): bool {
        if (!is_array($dataModel)) {
            return false;
        }

        if (!(array_keys($dataModel) !== range(0, count($dataModel) - 1))) {
            return false;
        }

        // @todo what do we do with empty arrays?

        return true;
    }

    /**
     * @param mixed $dataModel
     *
     * @return bool
     */
    public function __invoke(
        $dataModel
    ): bool {
        return static::invoke($dataModel);
    }
}
