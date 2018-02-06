<?php

namespace Reliv\PipeRat2\DataFieldList\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrderValues;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidOrder;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidOrderAllowedFields extends AssertValidOrderValues implements AssertValidOrder
{
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

        $allowedFields = $request->getAttribute(
            WithRequestAttributeAllowedFields::ATTRIBUTE
        );

        if (empty($allowedFields)) {
            throw new InvalidOrder(
                'No allowed fields found to validate order'
            );
        }

        $this->assertValid(
            $allowedFields,
            $order
        );

        parent::__invoke(
            $request,
            $options
        );
    }

    /**
     * @param array $allowedFields
     * @param array $order
     *
     * @return void
     * @throws InvalidOrder
     */
    protected function assertValid(
        array $allowedFields,
        array $order
    ) {
        foreach ($order as $fieldName => $orderValue) {
            if (!array_key_exists($fieldName, $allowedFields)) {
                throw new InvalidOrder(
                    'Field is not allowed in where: ' . $fieldName
                );
            }
        }
    }
}
