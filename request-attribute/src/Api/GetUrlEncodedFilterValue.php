<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUrlEncodedFilterValue
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $urlParamKey
     *
     * @return mixed|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $urlParamKey
    ) {
        $params = $request->getQueryParams();

        if (!array_key_exists('filter', $params)
            || !array_key_exists($urlParamKey, $params['filter'])
        ) {
            //Nothing in params for us so leave
            return null;
        }

        return $params['filter'][$urlParamKey];
    }
}
