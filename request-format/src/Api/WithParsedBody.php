<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestFormat\Exception\RequestFormatDecodeFail;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithParsedBody
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     * @throws RequestFormatDecodeFail
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface;
}
