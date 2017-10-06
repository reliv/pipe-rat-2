<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributeFields extends RequestAttributeAbstractUrlEncodedFilters
{
    const ATTRIBUTE = 'pipe-rat-request-attribute-fields-param';

    /**
     * Is used by parent getValue() function
     */
    const URL_KEY = 'fields';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attribute-fields';
    }

    /**
     * @param GetOptions $getOptions
     */
    public function __construct(GetOptions $getOptions)
    {
        parent::__construct($getOptions);
    }

    /**
     * Get the param from the URL
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     * @throws InvalidRequestAttribute
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        return $next($request->withAttribute(self::ATTRIBUTE, $this->getValue($request)), $response);
    }
}