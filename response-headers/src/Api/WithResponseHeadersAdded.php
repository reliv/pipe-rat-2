<?php

namespace Reliv\PipeRat2\ResponseHeaders\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithResponseHeadersAdded implements WithResponseHeaders
{
    const OPTION_HEADERS = 'headers';
    const DEFAULT_HEADERS = [];

    protected $defaultHeaders;

    public function __construct(
        array $defaultHeaders = self::DEFAULT_HEADERS
    ) {
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ):ResponseInterface {
        $headers = Options::get(
            $options,
            self::OPTION_HEADERS,
            $this->defaultHeaders
        );

        if (empty($headers)) {
            return $response;
        }

        foreach ($headers as $values) {
            if (!array_key_exists('name', $values) || !array_key_exists('value', $values)) {
                throw new \Exception('Header config requires both a name and a value key');
            }

            $response = $response->withAddedHeader($values['name'], $values['value']);
        }

        return $response;
    }
}
