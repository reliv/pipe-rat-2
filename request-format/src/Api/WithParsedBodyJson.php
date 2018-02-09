<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Exception\JsonError;
use Reliv\PipeRat2\Core\Json;
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
        try {
            $body = Json::decode($contents, true);
        } catch (JsonError $exception) {
            throw new RequestFormatDecodeFail(
                $exception->getMessage()
            );
        }

        return $request->withParsedBody($body);
    }
}
