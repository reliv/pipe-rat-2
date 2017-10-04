<?php

namespace Reliv\PipeRat2\HttpData\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\HttpData\BasicDataResponse;
use Reliv\PipeRat2\HttpData\DataResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseWithDataBodyDataResponse implements ResponseWithDataBody
{
    public function __invoke(
        ResponseInterface $response,
        $dataModel
    ) {
        if (!$response instanceof DataResponse) {
            $response = new BasicDataResponse(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders()
            );
        }

        return $response->withDataBody($dataModel);
    }
}
