<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Validate
{
    const OPTION_PRIMARY_MESSAGE = 'primary-message';

    /**
     * @param $data
     * @param array $options
     *
     * @return mixed
     */
    public function __invoke(
        $data,
        array $options = []
    ): ValidateResult;
}
