<?php

namespace Reliv\PipeRat2\DataError\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetErrorArray
{
    const DEFAULT_MESSAGE = 'An error occurred';

    /**
     * @param string      $message
     * @param string|null $code
     * @param array|null  $fieldMessages
     *
     * @return array
     */
    public static function invoke(
        string $message = '',
        string $code = null,
        array $fieldMessages = null
    ):array {
        if (empty($message)) {
            $message = static::DEFAULT_MESSAGE;
        }
        $error = [
            'error' => $message,
        ];

        if ($code !== null) {
            $error['code'] = $code;
        }

        if (is_array($fieldMessages)) {
            $error['fieldMessages'] = $fieldMessages;
        }

        return $error;
    }

    /**
     * @param string      $message
     * @param string|null $code
     * @param array|null  $fieldMessages
     *
     * @return array
     */
    public function __invoke(
        string $message = '',
        string $code = null,
        array $fieldMessages = null
    ): array {
        return static::invoke(
            $message,
            $code,
            $fieldMessages
        );
    }
}
