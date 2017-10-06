<?php

namespace Reliv\PipeRat2\DataValidate\ValidateResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ValidateResult extends \JsonSerializable
{
    /**
     * @return bool
     */
    public function isValid():bool;

    /**
     * @return string
     */
    public function getPrimaryMessage():string;

    /**
     * @return array ['{field-name}' => '{message}'] | ['{field-name}' => ['{message}']]
     */
    public function getFieldMessages():array;
}
