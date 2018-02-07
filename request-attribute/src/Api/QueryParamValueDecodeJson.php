<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Reliv\PipeRat2\Core\Exception\JsonError;
use Reliv\PipeRat2\Core\Json;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class QueryParamValueDecodeJson implements QueryParamValueDecode
{
    /**
     * @param string|array $paramValue
     * @param array        $options
     *
     * @return array|mixed
     */
    public function __invoke(
        $paramValue,
        array $options = []
    ) {
        return $this->prepare($paramValue);
    }

    /**
     * @param $paramValue
     *
     * @return mixed
     */
    protected function prepare($paramValue)
    {
        if (is_array($paramValue)) {
            return $this->prepareArray($paramValue);
        }

        try {
            $value = Json::decode($paramValue);
        } catch (JsonError $e) {
            $value = $paramValue;
        }

        return $value;
    }

    /**
     * @param array $paramValue
     *
     * @return array
     */
    protected function prepareArray(array $paramValue)
    {
        $prepared = [];

        foreach ($paramValue as $key => $value) {
            $prepared[$key] = $this->prepare($value);
        }

        return $prepared;
    }
}
