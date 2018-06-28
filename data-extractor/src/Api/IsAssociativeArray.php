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
        // it's the consumer's responsibility to determine that an array is empty and respond accordingly.
        // an empty array is in a superstate of being both associative and non-associative at the same time.
        // therefore asking  "is this associative" would always return true,
        // but asking "is this sequential" would *also* always return true.
        // and remember, in php, all arrays are *technically* associative no matter what.

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
