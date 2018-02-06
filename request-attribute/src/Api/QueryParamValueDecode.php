<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface QueryParamValueDecode
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
    );
}
