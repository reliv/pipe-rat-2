<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUrlEncodedFilterValue
{
    protected $queryParamValueDecode;

    /**
     * @param QueryParamValueDecode $queryParamValueDecode
     */
    public function __construct(
        QueryParamValueDecode $queryParamValueDecode
    ) {
        $this->queryParamValueDecode = $queryParamValueDecode;
    }

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

        if (!array_key_exists('filter', $params))
        {
            // No filter
            return null;
        }

        $filterParams = $this->queryParamValueDecode->__invoke(
            $params['filter']
        );

        if (!array_key_exists($urlParamKey, $filterParams))
        {
            // No params for us so leave
            return null;
        }

        return $filterParams[$urlParamKey];
    }
}
