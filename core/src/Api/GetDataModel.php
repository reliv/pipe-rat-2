<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetDataModel
{
    /**
     * @param ResponseInterface $response
     * @param null              $default
     *
     * @return mixed|null
     */
    public function __invoke(
        ResponseInterface $response,
        $default = null
    );
}
