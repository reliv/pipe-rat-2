<?php

namespace Reliv\PipeRat2\DataValueTypes\Service;

use Reliv\PipeRat2\DataValueTypes\Exception\InvalidValueType;
use Reliv\PipeRat2\DataValueTypes\Exception\UnknownValueType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ValueTypes
{
    /*
     * (json-encode-able) int, string, bool, null, array, basic object
     */
    const PRIMITIVE = 'primitive';

    /**
     * object, associative array, null
     */
    const OBJECT = 'object';

    /**
     * array, traversable
     */
    const COLLECTION = 'collection';

    /**
     * @param mixed $dataModel
     * @param array $options
     *
     * @return string
     * @throws UnknownValueType
     */
    public function getType(
        $dataModel,
        array $options = []
    ): string;

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param array  $options
     *
     * @return bool
     * @throws UnknownValueType
     */
    public function isType(
        $dataModel,
        string $type,
        array $options = []
    ): bool;

    /**
     * @param mixed  $dataModel
     * @param string $type
     * @param array  $options
     *
     * @return void
     * @throws UnknownValueType|InvalidValueType
     */
    public function assertType(
        $dataModel,
        string $type,
        array $options = []
    );
}
