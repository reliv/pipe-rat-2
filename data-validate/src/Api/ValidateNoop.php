<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResult;
use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResultBasic;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateNoop implements Validate
{
    protected $defaultMessage;

    /**
     * @param string|null $defaultMessage
     */
    public function __construct(
        string $defaultMessage = null
    ) {
        if ($defaultMessage === null) {
            $this->defaultMessage = 'No validation done: ' . get_class($this);
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
            self::OPTION_PRIMARY_MESSAGE,
            $this->defaultMessage
        );

        return new ValidateResultBasic(
            true,
            $message,
            $data
        );
    }
}
