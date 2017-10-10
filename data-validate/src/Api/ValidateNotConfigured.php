<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResult;
use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResultBasic;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateNotConfigured implements Validate
{
    const OPTION_MESSAGE = 'message';
    const DEFAULT_MESSAGE = 'Validation has not been configured';
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
     * @param       $data
     * @param array $options
     *
     * @return ValidateResult
     * @throws \Exception
     */
    public function __invoke(
        $data,
        array $options = []
    ): ValidateResult
    {
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

        return new ValidateResultBasic(
            true,
            $message
        );
    }
}
