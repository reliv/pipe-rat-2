<?php

namespace Reliv\PipeRat2\HttpData\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ResponseWithDataBody
{
    /**
     * @param ResponseInterface $response
     * @param                   $dataModel
     *
     * @return mixed
     */
    public function __invoke(
        ResponseInterface $response,
        $dataModel
    );
}
