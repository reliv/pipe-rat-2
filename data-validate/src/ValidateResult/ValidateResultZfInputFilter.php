<?php

namespace Reliv\PipeRat2\DataValidate\ValidateResult;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateResultZfInputFilter extends ValidateResultAbstract implements ValidateResult
{
    /**
     * @param bool   $valid
     * @param string $input
     * @param string $message
     * @param null   $validData
     */
    public function __construct(
        $valid,
        $input,
        string $message = '',
        $validData = null
    ) {
        $fieldMessages = $this->build(
            $input
        );
        parent::__construct(
            $valid,
            $message,
            $validData,
            $fieldMessages
        );
    }

    /**
     * getParseName
     *
     * @param string $name
     * @param string $key
     * @param string $subInput
     *
     * @return string
     */
    protected function getParseName($name, $key, $subInput)
    {
        $fieldName = $key;
        if (method_exists($subInput, 'getName')) {
            $fieldName = $subInput->getName();
        }
        if ($name !== '') {
            $fieldName = $name . '[' . $fieldName . ']';
        }

        return $fieldName;
    }

    /**
     * buildValidatorMessages
     *
     * @param string $fieldName
     * @param Input  $input
     * @param array  $errors
     *
     * @return array
     */
    protected function buildValidatorMessages(
        $fieldName,
        Input $input,
        $errors = []
    ) {
        $validatorChain = $input->getValidatorChain();
        $validators = $validatorChain->getValidators();

        // We get the input messages because input does validations outside of the validators
        $allMessages = $input->getMessages();

        foreach ($validators as $fkey => $validatorData) {
            /** @var \Zend\Validator\AbstractValidator $validator */
            $validator = $validatorData['instance'];

            $inputMessages = $validator->getMessages();

            // Remove the messages from $allMessages as we get them from the validators
            $allMessages = array_diff($allMessages, $inputMessages);

            $errors = $this->buildErrors($fieldName, $inputMessages, $errors);
        }

        // get any remaining messages that did not come from validators
        return $this->buildErrors($fieldName, $allMessages, $errors);
    }

    /**
     * buildErrors
     *
     * @param string $fieldName
     * @param array  $inputMessages
     * @param array  $errors
     *
     * @return array
     */
    protected function buildErrors(
        $fieldName,
        $inputMessages,
        $errors = []
    ) {
        foreach ($inputMessages as $errorKey => $message) {
            $errors[] = [
                'message' => $message,
                'field' => $fieldName,
                'code' => $errorKey,
            ];
        }

        return $errors;
    }

    /**
     * parseInputs
     *
     * @param        $input
     * @param string $name
     * @param array  $errors
     *
     * @return array $errors
     */
    protected function build($input, $name = '', $errors = [])
    {
        if (is_array($input)) {
            foreach ($input as $key => $subInput) {
                $fieldName = $this->getParseName($name, $key, $subInput);
                $errors = $this->build($subInput, $fieldName, $errors);
            }

            return $errors;
        }

        if ($input instanceof CollectionInputFilter) {
            $inputs = $input->getInvalidInput();
            foreach ($inputs as $groupKey => $group) {
                $fieldName = $this->getParseName($name, $groupKey, $group);
                $errors = $this->build($group, $fieldName, $errors);
            }

            return $errors;
        }

        if ($input instanceof InputFilter) {
            $inputs = $input->getInvalidInput();

            foreach ($inputs as $key => $subInput) {
                $fieldName = $this->getParseName($name, $key, $subInput);
                $errors = $this->build($subInput, $fieldName, $errors);
            }

            return $errors;
        }

        return $this->buildValidatorMessages($name, $input, $errors);
    }
}
