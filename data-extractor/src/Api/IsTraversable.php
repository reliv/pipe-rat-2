<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsTraversable
{
    /**
     * @param mixed $dataModel
     *
     * @return bool
     */
    public static function invoke($dataModel): bool
    {
        return (is_array($dataModel) || $dataModel instanceof \Traversable);
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
