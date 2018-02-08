<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidWhere;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidWhereAllowedFields implements AssertValidWhere
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
     * @throws InvalidWhere
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $where = $request->getAttribute(
            WithRequestAttributeWhere::ATTRIBUTE
        );

        if (empty($where)) {
            return;
        }

        $allowedFieldConfig = $request->getAttribute(
            WithRequestAttributeAllowedFieldConfig::ATTRIBUTE
        );

        if (empty($allowedFieldConfig)) {
            throw new InvalidWhere(
                'No allowed fields found to validate where'
            );
        }

        $this->assertValid(
            $allowedFieldConfig,
            $where
        );
    }

    /**
     * @param array $allowedFieldConfig
     * @param array $where
     *
     * @return void
     * @throws InvalidWhere
     */
    protected function assertValid(
        array $allowedFieldConfig,
        array $where
    ) {
        $allowedProperties = $this->fieldConfig->getProperties($allowedFieldConfig);
        
        if (empty($allowedProperties) && is_array($where)) {
            throw new InvalidWhere(
                'Where is not allowed: ' . Json::encode($where)
            );
        }

        foreach ($where as $fieldName => $whereValue) {
            if (!array_key_exists($fieldName, $allowedProperties)) {
                throw new InvalidWhere(
                    'Field is not allowed in where: ' . $fieldName
                );
            }

            if (is_array($whereValue)) {
                $this->assertValid(
                    $allowedProperties[$fieldName],
                    $whereValue
                );
            }
        }
    }
}
