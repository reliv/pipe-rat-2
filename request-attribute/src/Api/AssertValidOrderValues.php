<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidOrder;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidOrderValues implements AssertValidOrder
{
    protected $validOrderValues
        = [
            'ASC',
            'DESC'
        ];

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return void
     * @throws InvalidOrder
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $order = $request->getAttribute(
            WithRequestAttributeOrder::ATTRIBUTE
        );

        if (empty($order)) {
            return;
        }

        foreach ($order as $fieldName => $orderValue) {
            if (!in_array($orderValue, $this->validOrderValues)) {
                throw new InvalidOrder(
                    'Order value is not supported: ' . $orderValue
                );
            }
        }
    }
}
