<?php

namespace Reliv\PipeRat2\ResponseHeaders\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithResponseHeadersCacheMaxAge implements WithResponseHeaders
{
    const OPTION_HTTP_METHODS = 'httpMethods';
    const OPTION_PRAGMA = 'pragma';
    const OPTION_MAX_AGE = 'max-age';

    const DEFAULT_HTTP_METHODS = ['GET', 'OPTIONS', 'HEAD'];
    const DEFAULT_PRAGMA = 'cache';
    const DEFAULT_MAX_AGE = '3600';

    protected $defaultHttpMethods;
    protected $defaultPragma;
    protected $defaultMaxAge;

    /**
     * @param array  $defaultHttpMethods
     * @param string $defaultPragma
     * @param string $defaultMaxAge
     */
    public function __construct(
        array $defaultHttpMethods = self::DEFAULT_HTTP_METHODS,
        string $defaultPragma = self::DEFAULT_PRAGMA,
        $defaultMaxAge = self::DEFAULT_MAX_AGE
    ) {
        $this->defaultHttpMethods = $defaultHttpMethods;
        $this->defaultPragma = $defaultPragma;
        $this->defaultMaxAge = $defaultMaxAge;
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
        $httpMethods = Options::get(
            $options,
            self::OPTION_HTTP_METHODS,
            $this->defaultHttpMethods
        );

        if (!in_array($request->getMethod(), $httpMethods)) {
            return $response;
        }

        $pragma = Options::get(
            $options,
            self::OPTION_PRAGMA,
            $this->defaultPragma
        );

        $maxAge = Options::get(
            $options,
            self::OPTION_MAX_AGE,
            $this->defaultMaxAge
        );

        // $lastModifiedDefault = new \DateTime('@0');
        // $lastModified = $lastModifiedDefault->format('D, d M Y H:i:s') . ' GMT';
        // ->withHeader('Last-Modified', $lastModified)

        $maxAgeValue = "max-age={$maxAge}";

        return $response->withHeader('cache-control', $maxAgeValue)->withHeader('pragma', $pragma);
    }
}
