<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Core\DataResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsResponseFormattableBasic implements IsResponseFormattable
{
    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function __invoke(
        ResponseInterface $response
    ):bool {
        if ($response instanceof DataResponse) {
            return true;
        }

        if ($response instanceof JsonResponse) {
            return true;
        }

        return false;
    }
}
