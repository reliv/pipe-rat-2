<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Api\AssertValidWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidWhereAllowedFields implements AssertValidWhere
{
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

        $allowedFields = $request->getAttribute(
            WithRequestAttributeAllowedFieldConfig::ATTRIBUTE
        );

        if (empty($allowedFields)) {
            throw new InvalidWhere(
                'No allowed fields found to validate where'
            );
        }

        $this->assertValid(
            $allowedFields,
            $where
        );
    }

    /**
     * @param array $allowedFields
     * @param array $where
     *
     * @return void
     * @throws InvalidWhere
     */
    protected function assertValid(
        array $allowedFields,
        array $where
    ) {
        foreach ($where as $fieldName => $whereValue) {
            if (!array_key_exists($fieldName, $allowedFields)) {
                throw new InvalidWhere(
                    'Field is not allowed in where: ' . $fieldName
                );
            }

            if (is_array($whereValue)) {
                $this->assertValid(
                    $allowedFields[$fieldName],
                    $whereValue
                );
            }
        }
    }
}
