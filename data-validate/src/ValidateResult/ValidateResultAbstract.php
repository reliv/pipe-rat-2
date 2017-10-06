<?php

namespace Reliv\PipeRat2\DataValidate\ValidateResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class ValidateResultAbstract
{
    protected $valid = true;
    protected $primaryMessage = '';
    /**
     * @var array ['{field-name}' => '{message}']
     */
    protected $fieldMessages = [];

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
        $this->valid = $valid;
        $this->primaryMessage = $primaryMessage;
        $this->fieldMessages = $fieldMessages;
    }

    /**
     * @return bool
     */
    public function isValid():bool
    {
        return $this->valid;
    }

    /**
     * @return string
     */
    public function getPrimaryMessage():string
    {
        return $this->primaryMessage;
    }

    /**
     * @return array
     */
    public function getFieldMessages():array
    {
        return $this->fieldMessages;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'valid' => $this->valid,
            'primaryMessage' => $this->primaryMessage,
            'fieldMessages' => $this->fieldMessages,
        ];
    }
}
