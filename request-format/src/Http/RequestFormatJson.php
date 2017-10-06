<?php

namespace Reliv\PipeRat2\RequestFormat\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestFormatJson extends RequestFormatAbstract
{
    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-format-json';
    }

    /**
     * @var array
     */
    protected $defaultContentTypes
        = [
            'application/json',
        ];

    /**
     * If the request is of type application/json, this middleware
     * decodes the json in the body and puts it in the "body" attribute
     * in the request.
     *
     * @param Request       $request
     * @param Response      $response
     * @param null|callable $next
     *
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if (!$this->isValidMethod($request)) {
            return $next($request, $response);
        }

        if ($this->isValidContentType($request)) {
            $body = json_decode($request->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $body = $response->getBody();
                $body->write(
                    'MIME type was "application/json" but invalid JSON in request body.'
                );

                return $response->withStatus(400)->withBody($body);
            }

            $request = $request->withParsedBody($body);
        }

        return $next($request, $response);
    }
}
