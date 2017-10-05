<?php

namespace Reliv\PipeRat2\ResponseHeaders\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Http\MiddlewareWithConfigOptionsAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseHeadersAdd extends MiddlewareWithConfigOptionsAbstract
{
    const OPTION_HEADERS = 'headers';

    public static function configKey(): string
    {
        return 'response-headers-add';
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        /** @var ResponseInterface $response */
        $response = $next($request);

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $headers = Options::get($options, self::OPTION_HEADERS, []);

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
