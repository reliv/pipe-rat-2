<?php

namespace Reliv\PipeRat2\HttpData\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetDataModel
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
    );
}
