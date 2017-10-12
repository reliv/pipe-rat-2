<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidWhere;

/**
 * @deprecated
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributeUrlEncodedFiltersWhere
    extends RequestAttributeUrlEncodedFiltersAbstract
    implements RequestAttributeWhere
{
    /**
     * Is used by parent getValue() function
     */
    const URL_KEY = 'where';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attribute-where';
    }

    /**
     * @param GetOptions $getOptions
     */
    public function __construct(GetOptions $getOptions)
    {
        parent::__construct($getOptions);
    }

    /**
     * Get the where param form the URL.
     *
     * Looks like:{"country":"CAN"} or {"country":{"name":"United States"}}
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     * @throws InvalidWhere
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $where = $this->getValue($request);

        if ($where === null) {
            return $next($request, $response);
        }

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $allowDeepWheres = Options::get(
            $options,
            self::OPTION_ALLOW_DEEP_WHERES,
            false
        );

        if ($allowDeepWheres) {
            return $next($request->withAttribute(self::ATTRIBUTE, $where), $response);
        }

        foreach ($where as $whereChunk) {
            if (is_array($whereChunk)) {
                //Should this be 400'ing instead of throwing?
                throw new InvalidWhere();
            }
        }

        return $next($request->withAttribute(self::ATTRIBUTE, $where), $response);
    }
}
