<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeUrlEncodedWhere implements WithRequestAttributeWhere
{
    /* deprecated */
    //const OPTION_ALLOW_DEEP_WHERES = 'allow-deep-wheres';
    /* deprecated */
    //const DEFAULT_ALLOW_DEEP_WHERES = false;

    const URL_KEY = 'where';

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

        return $request->withAttribute(self::ATTRIBUTE, $where);
    }
}
