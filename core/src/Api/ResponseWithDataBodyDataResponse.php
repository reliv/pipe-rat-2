<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\DataResponse;

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
            $response = new DataResponseBasic(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders()
            );
        }

        return $response->withDataBody($dataModel);
    }
}
