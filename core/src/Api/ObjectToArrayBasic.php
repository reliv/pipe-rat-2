<?php

namespace Reliv\PipeRat2\Core\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ObjectToArrayBasic implements ObjectToArray
{
    /**
     * @param object $dataModel
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        $dataModel
    ): array {
        if (!is_object($dataModel)) {
            throw new \Exception(
                'Data model is not object'
            );
        }

        $method = 'toArray';
        if (method_exists($dataModel, $method)) {
            $value = $dataModel->$method();
            $this->assertIsArray($value, 'toArray');

            return $value;
        }

        $method = '_toArray';
        if (method_exists($dataModel, $method)) {
            $value = $dataModel->$method();
            $this->assertIsArray($value, '_toArray');

            return $value;
        }

        $method = '__toArray';
        if (method_exists($dataModel, $method)) {
            $value = $dataModel->$method();
            $this->assertIsArray($value, '__toArray');

            return $value;
        }

        $method = 'jsonSerialize';
        if (method_exists($dataModel, $method)) {
            $value = $dataModel->$method();
            $this->assertIsArray($value, 'jsonSerialize');

            return $value;
        }

        return get_object_vars($dataModel);
    }

    /**
     * @param $value
     * @param $context
     *
     * @return void
     * @throws \Exception
     */
    protected function assertIsArray($value, $context)
    {
        if (!is_array($value)) {
            throw new \Exception(
                'Value is not array from: ' . $context
            );
        }
    }
}
