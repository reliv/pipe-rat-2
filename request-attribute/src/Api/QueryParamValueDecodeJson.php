<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

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
            $value = $this->decode($paramValue);
        } catch (\Exception $e) {
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

    /**
     * @param string $json
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     * @param string $context
     *
     * @return mixed
     * @throws \Exception
     */
    protected function decode(
        string $json,
        bool $assoc = true,
        int $depth = 512,
        int $options = 0,
        string $context = ''
    ) {
        // Clear json_last_error()
        json_encode(null);

        $value = json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(
                sprintf(
                    'Unable to decode JSON: %s. %s',
                    json_last_error_msg(),
                    $context
                )
            );
        }

        return $value;
    }
}
