<?php

namespace Reliv\PipeRat2\ResponseHeaders\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Http\MiddlewareWithConfigOptionsAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * Middleware to send Expires header.
 *
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseHeadersCacheMaxAge extends MiddlewareWithConfigOptionsAbstract
{
    const OPTION_HTTP_METHODS = 'httpMethods';
    const OPTION_PRAGMA = 'pragma';
    const OPTION_MAX_AGE = 'max-age';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-headers-cache-max-age';
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
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

        $httpMethods = Options::get(
            $options,
            self::OPTION_HTTP_METHODS,
            ['GET', 'OPTIONS', 'HEAD']
        );

        if (!in_array($request->getMethod(), $httpMethods)) {
            return $response;
        }

        $pragma = Options::get(
            $options,
            self::OPTION_PRAGMA,
            'cache'
        );
        $maxAge = Options::get(
            $options,
            'max-age',
            '3600'
        );

        // $lastModifiedDefault = new \DateTime('@0');
        // $lastModified = $lastModifiedDefault->format('D, d M Y H:i:s') . ' GMT';
        // ->withHeader('Last-Modified', $lastModified)

        $maxAgeValue = "max-age={$maxAge}";

        return $response->withHeader('cache-control', $maxAgeValue)->withHeader('pragma', $pragma);
    }
}
