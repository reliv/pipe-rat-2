<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsResponseFormattable
{
    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function __invoke(
        ResponseInterface $response
    ):bool;
}
