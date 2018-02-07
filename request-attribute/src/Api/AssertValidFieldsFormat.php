<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidFields;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidFieldsFormat implements AssertValidFields
{
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

        $this->assertValid($fields);
    }

    /**
     * @param $fields
     *
     * @return void
     * @throws InvalidFields
     */
    protected function assertValid($fields)
    {
        foreach ($fields as $value) {
            $this->assertValidValue($value);
            if (is_array($value)) {
                $this->assertValid($value);
            }
        }
    }

    /**
     * @param array|bool $value
     *
     * @return void
     * @throws InvalidFields
     */
    protected function assertValidValue($value)
    {
        if (!is_array($value) && !is_bool($value)) {
            throw new InvalidFields(
                'Fields must be array or bool for value: ' . Json::encode($value)
            );
        }
    }
}
