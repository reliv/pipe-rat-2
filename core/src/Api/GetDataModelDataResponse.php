<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Core\DataResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetDataModelDataResponse implements GetDataModel
{
    /**
     * @param ResponseInterface|DataResponse|JsonResponse $response
     * @param null                                        $default
     *
     * @return mixed|null
     */
    public function __invoke(
        ResponseInterface $response,
        $default = null
    ) {
        if ($response instanceof DataResponse) {
            return $response->getDataBody();
        }

        if ($response instanceof JsonResponse) {
            return $response->getPayload();
        }

        return $default;
    }
}
