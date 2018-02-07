<?php

namespace Reliv\PipeRat2\Core;

use Reliv\PipeRat2\Core\Exception\JsonError;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Json
{
    /**
     * @param mixed  $value
     * @param int    $options
     * @param int    $depth
     * @param string $context
     *
     * @return string
     * @throws JsonError
     */
    public static function encode(
        $value,
        int $options = 0,
        int $depth = 512,
        string $context = ''
    ): string {
        // Clear json_last_error()
        json_encode(null);

        $json = json_encode($value, $options, $depth);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonError(
                sprintf(
                    'Unable to encode data to JSON: %s. %s',
                    json_last_error_msg(),
                    $context
                )
            );
        }

        return $json;
    }

    /**
     * @param string $json
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     * @param string $context
     *
     * @return mixed
     * @throws JsonError
     */
    public static function decode(
        string $json,
        bool $assoc = true,
        int $depth = 512,
        int $options = 0,
        string $context = ''
    ) {
        // Clear json_last_error()
        json_encode(null);

        $value = json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonError(
                sprintf(
                    'Unable to decode JSON: %s. %s',
                    json_last_error_msg(),
                    $context
                )
            );
        }

        return $value;
    }
}
