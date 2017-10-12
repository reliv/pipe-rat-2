<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestFormat\Exception\RequestFormatDecodeFail;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithParsedBodyJson implements WithParsedBody
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
    ): ServerRequestInterface {
        $contents = $request->getBody()->getContents();
        $body = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RequestFormatDecodeFail(
                "Invalid JSON in request body: \n" . $contents
            );
        }

        return $request->withParsedBody($body);
    }
}
