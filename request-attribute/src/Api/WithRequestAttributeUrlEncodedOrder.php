<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeUrlEncodedOrder implements WithRequestAttributeOrder
{
    const URL_KEY = 'order';

    protected $getUrlEncodedFilterValue;

    /**
     * @param GetUrlEncodedFilterValue $getUrlEncodedFilterValue
     */
    public function __construct(
        GetUrlEncodedFilterValue $getUrlEncodedFilterValue
    ) {
        $this->getUrlEncodedFilterValue = $getUrlEncodedFilterValue;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     * @throws InvalidRequestAttribute
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface {
        $value = $this->getUrlEncodedFilterValue->__invoke(
            $request,
            self::URL_KEY
        );

        if ($value !== null && !is_array($value)) {
            throw new InvalidRequestAttribute(
                'Order must be array'
            );
        }

        return $request->withAttribute(self::ATTRIBUTE, $value);
    }
}
