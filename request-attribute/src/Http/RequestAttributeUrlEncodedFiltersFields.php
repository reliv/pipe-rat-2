<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @deprecated
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributeUrlEncodedFiltersFields
    extends RequestAttributeUrlEncodedFiltersAbstract
    implements RequestAttributeFields
{
    /**
     * Is used by parent getValue() function
     */
    const URL_KEY = 'fields';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attribute-fields-url-encoded-filters';
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
        $fields = $this->getValue($request);

        if ($fields === null) {
            return $next(
                $request,
                $response
            );
        }

        foreach ($fields as $key => $value) {
            $fields[$key] = ($value == 'true' || $value == '1' ? true : false);
        }

        return $next(
            $request->withAttribute(self::ATTRIBUTE, $fields),
            $response
        );
    }
}
