<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeUrlEncodedFields implements WithRequestAttributeFields
{
    const URL_KEY = 'fields';

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
        $fields = $this->getUrlEncodedFilterValue->__invoke(
            $request,
            self::URL_KEY
        );

        if ($fields === null) {
            return $request;
        }

        if (!is_array($fields)) {
            throw new InvalidRequestAttribute(
                'Fields must be array'
            );
        }

        foreach ($fields as $key => $value) {
            $fields[$key] = ($value == 'true' || $value == '1' ? true : false);
        }

        return $request->withAttribute(self::ATTRIBUTE, $fields);
    }
}
