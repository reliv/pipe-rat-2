<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsAbstract;

abstract class RequestAttributeUrlEncodedFiltersAbstract extends MiddlewareWithConfigOptionsAbstract
{
    /**
     * Over-ride me
     */
    const URL_KEY = '@override-me';

    /**
     * getValue
     *
     * @param ServerRequestInterface $request
     *
     * @return null|mixed
     */
    protected function getValue(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();

        if (!array_key_exists('filter', $params)
            || !array_key_exists(static::URL_KEY, $params['filter'])
        ) {
            //Nothing in params for us so leave
            return null;
        }

        return $params['filter'][static::URL_KEY];
    }
}
