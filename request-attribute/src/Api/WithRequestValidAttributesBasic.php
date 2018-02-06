<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestValidAttributesBasic implements WithRequestValidAttributes
{
    protected $assertValidFields;
    protected $assertValidLimit;
    protected $assertValidOrder;
    protected $assertValidSkip;
    protected $assertValidWhere;

    public function __construct(
        AssertValidFields $assertValidFields,
        AssertValidLimit $assertValidLimit,
        AssertValidOrder $assertValidOrder,
        AssertValidSkip $assertValidSkip,
        AssertValidWhere $assertValidWhere
    ) {
        $this->assertValidFields = $assertValidFields;
        $this->assertValidLimit = $assertValidLimit;
        $this->assertValidOrder = $assertValidOrder;
        $this->assertValidSkip = $assertValidSkip;
        $this->assertValidWhere = $assertValidWhere;
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
        $this->assertValidFields->__invoke(
            $request,
            $options
        );
        $this->assertValidLimit->__invoke(
            $request,
            $options
        );
        $this->assertValidOrder->__invoke(
            $request,
            $options
        );
        $this->assertValidSkip->__invoke(
            $request,
            $options
        );
        $this->assertValidWhere->__invoke(
            $request,
            $options
        );

        return $request;
    }
}
