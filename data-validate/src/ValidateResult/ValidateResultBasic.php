<?php

namespace Reliv\PipeRat2\DataValidate\ValidateResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateResultBasic extends ValidateResultAbstract implements ValidateResult
{
    /**
     * @param bool   $valid
     * @param string $primaryMessage
     * @param array  $fieldMessages
     */
    public function __construct(
        bool $valid = true,
        string $primaryMessage = '',
        array $fieldMessages = []
    ) {
        parent::__construct(
            $valid,
            $primaryMessage,
            $fieldMessages
        );
    }
}
