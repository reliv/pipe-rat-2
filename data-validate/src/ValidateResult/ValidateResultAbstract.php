<?php

namespace Reliv\PipeRat2\DataValidate\ValidateResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class ValidateResultAbstract
{
    protected $valid = true;
    protected $message = '';
    /**
     * @var array ['{field-name}' => '{message}']
     */
    protected $fieldMessages = [];
    protected $validData = null;

    /**
     * @param bool   $valid
     * @param string $message
     * @param null   $validData
     * @param array  $fieldMessages
     */
    public function __construct(
        bool $valid = true,
        string $message = '',
        $validData = null,
        array $fieldMessages = []
    ) {
        $this->valid = $valid;
        $this->message = $message;
        $this->validData = $validData;
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
    public function getMessage():string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getFieldMessages():array
    {
        return $this->fieldMessages;
    }

    /**
     * Validated/Filtered data
     *
     * @return mixed
     */
    public function getValidData()
    {
        return $this->validData;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'valid' => $this->valid,
            'error' => $this->message,
            'fieldMessages' => $this->fieldMessages,
        ];
    }
}
