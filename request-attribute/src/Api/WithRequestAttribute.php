<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithRequestAttribute
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     * @throws InvalidRequestAttribute
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface;
}
