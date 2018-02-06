<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidWhereNoDeepWheres implements AssertValidWhere
{
    const OPTION_ALLOW_DEEP_WHERES = 'allow-deep-wheres';

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

        if ($where === null) {
            return;
        }

        $allowDeepWheres = Options::get(
            $options,
            self::OPTION_ALLOW_DEEP_WHERES,
            false
        );

        if ($allowDeepWheres) {
            return;
        }

        foreach ($where as $whereChunk) {
            if (is_array($whereChunk)) {
                throw new InvalidWhere(
                    'Nested where params are not allowed'
                );
            }
        }
    }
}
