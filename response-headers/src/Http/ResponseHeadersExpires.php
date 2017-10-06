<?php
namespace Reliv\PipeRat2\ResponseHeaders\Http;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @todo   Make this work
 * Middleware to send Expires header.
 *
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseHeadersExpires extends MiddlewareWithConfigOptionsAbstract
{
    const OPTION_HTTP_METHODS = 'httpMethods';
    const OPTION_TIME = 'time';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-headers-expires';
    }

    /**
     * Execute the middleware.
     *
     * Example values for "expires" config. This is the same format apache uses in config.
     * +0 seconds
     * +1 hour
     * +1 day
     * +1 week
     * +1 month
     * +1 year
     *
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

        $expires = Options::get(
            $options,
            self::OPTION_TIME,
            null
        );
        $httpMethods = Options::get(
            $options,
            self::OPTION_HTTP_METHODS,
            ['GET', 'OPTIONS']
        );

        if (!in_array($request->getMethod(), $httpMethods)) {
            return $response;
        }

        if (!$expires) {
            return $response;
        }

        $expires = new DateTimeImmutable($expires);

        $cacheControl = $response->getHeaderLine('Cache-Control') ?: '';
        $cacheControl .= ' max-age=' . ($expires->getTimestamp() - time());

        return $response
            ->withHeader('Cache-Control', trim($cacheControl))
            ->withAddedHeader('Cache-Control', 'public')
            ->withHeader('Expires', $expires->format('D, d M Y H:i:s') . ' GMT')
            ->withHeader('Pragma', 'public');
    }
}
