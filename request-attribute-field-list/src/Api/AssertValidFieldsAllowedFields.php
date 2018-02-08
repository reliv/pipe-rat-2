<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidFields;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidFieldsFormat;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidFieldsAllowedFields extends AssertValidFieldsFormat implements AssertValidFields
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
     * @throws InvalidFields
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $fields = $request->getAttribute(
            WithRequestAttributeFields::ATTRIBUTE
        );

        if ($fields === null) {
            return;
        }

        if (!is_array($fields)) {
            throw new InvalidFields(
                'Fields must be array'
            );
        }

        $allowedFields = $request->getAttribute(
            WithRequestAttributeAllowedFieldConfig::ATTRIBUTE
        );

        $this->assertValidAllowed(
            $fields,
            $allowedFields
        );
    }

    /**
     * @param array $fields
     * @param array $allowedFieldConfig
     *
     * @return void
     * @throws InvalidFields
     */
    protected function assertValidAllowed(
        array $fields,
        array $allowedFieldConfig
    ) {
        if (!$this->fieldConfig->hasProperties($allowedFieldConfig)) {
            throw new InvalidFields(
                'Fields is not allowed: ' . Json::encode($fields)
            );
        }

        $allowedFieldConfigProperties = $this->fieldConfig->getProperties($allowedFieldConfig);

        foreach ($fields as $fieldName => $value) {
            // IF no whitelist config, then error
            if (!array_key_exists($fieldName, $allowedFieldConfigProperties)) {
                throw new InvalidFields(
                    'Field is not allowed: ' . $fieldName
                );
            }

            $this->assertValidValue($fieldName, $fields[$fieldName]);

            if (is_array($value)) {
                $this->assertValidAllowed($value, $allowedFieldConfigProperties[$fieldName]);
            }
        }
    }
}
