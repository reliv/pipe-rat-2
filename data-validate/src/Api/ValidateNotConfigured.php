<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResult;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateNotConfigured implements Validate
{
    const OPTION_MESSAGE = 'message';

    protected $defaultMessage;

    public function __construct(
        string $defaultMessage = null
    ) {
        if ($defaultMessage === null) {
            $this->defaultMessage = 'Validation is not configured: ' . get_class($this);
        }
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

        throw new \Exception($message);
    }
}
