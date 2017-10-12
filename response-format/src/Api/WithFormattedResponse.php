<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithFormattedResponse
{
    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ): ResponseInterface;
}
