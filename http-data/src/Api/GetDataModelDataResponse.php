<?php

namespace Reliv\PipeRat2\HttpData\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\HttpData\DataResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetDataModelDataResponse implements GetDataModel
{
    /**
     * @param ServerRequestInterface $response
     * @param null                   $default
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $response,
        $default = null
    ) {
        if ($response instanceof DataResponse) {
            return $response->getDataBody();
        }

        return $default;
    }
}
