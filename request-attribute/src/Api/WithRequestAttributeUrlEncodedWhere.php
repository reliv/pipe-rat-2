<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeUrlEncodedWhere implements WithRequestAttributeWhere
{
    const OPTION_ALLOW_DEEP_WHERES = 'allow-deep-wheres';

    const DEFAULT_ALLOW_DEEP_WHERES = false;

    const URL_KEY = 'where';

    protected $getUrlEncodedFilterValue;
    protected $defaultAllowDeepWheres;

    /**
     * @param GetUrlEncodedFilterValue $getUrlEncodedFilterValue
     * @param bool                     $defaultAllowDeepWheres
     */
    public function __construct(
        GetUrlEncodedFilterValue $getUrlEncodedFilterValue,
        bool $defaultAllowDeepWheres = self::DEFAULT_ALLOW_DEEP_WHERES
    ) {
        $this->getUrlEncodedFilterValue = $getUrlEncodedFilterValue;
        $this->defaultAllowDeepWheres = $defaultAllowDeepWheres;
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
        $where = $this->getUrlEncodedFilterValue->__invoke(
            $request,
            self::URL_KEY
        );

        if ($where === null) {
            return $request;
        }

        return $request->withAttribute(self::ATTRIBUTE, $where);
    }
}
