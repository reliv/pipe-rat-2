<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrder;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidOrderValues;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidOrder;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidOrderAllowedFields extends AssertValidOrderValues implements AssertValidOrder
{
    protected $fieldConfig;

    /**
     * @param FieldConfig $fieldConfig
     */
    public function __construct(
        FieldConfig $fieldConfig
    ) {
        $this->fieldConfig = $fieldConfig;
    }

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
            WithRequestAttributeAllowedFieldConfig::ATTRIBUTE
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
        $allowedProperties = $this->fieldConfig->getProperties($allowedFields);
        if (empty($allowedProperties) && is_array($order)) {
            throw new InvalidOrder(
                'Order is not allowed: ' . Json::encode($order)
            );
        }

        foreach ($order as $fieldName => $orderValue) {
            if (!array_key_exists($fieldName, $allowedFields)) {
                throw new InvalidOrder(
                    'Field is not allowed in order: ' . $fieldName
                );
            }
        }
    }
}
