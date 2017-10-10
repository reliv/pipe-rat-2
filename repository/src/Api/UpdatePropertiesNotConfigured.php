<?php

namespace Reliv\PipeRat2\Repository\Api;

use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdatePropertiesNotConfigured implements UpdateProperties
{
    const OPTION_MESSAGE = 'message';
    const DEFAULT_MESSAGE = 'UpdateProperties repository has not be configured';
    const DEFAULT_ERROR_TYPE = E_USER_WARNING;

    protected $defaultMessage;
    protected $errorType;

    /**
     * @param string|null $defaultMessage
     * @param int         $errorType
     */
    public function __construct(
        string $defaultMessage = self::DEFAULT_MESSAGE,
        int $errorType = self::DEFAULT_ERROR_TYPE
    ) {
        $this->defaultMessage = $defaultMessage;
        $this->errorType = $errorType;
    }

    /**
     * @param int|string $id
     * @param array      $properties
     * @param array      $options
     *
     * @return object|null $data
     */
    public function __invoke(
        $id,
        array $properties,
        array $options = []
    ) {
        $message = Options::get(
            $options,
            self::OPTION_MESSAGE,
            $this->defaultMessage
        );

        if ($this->errorType > 0) {
            trigger_error(
                $message,
                $this->errorType
            );
        }

        return null;
    }
}
