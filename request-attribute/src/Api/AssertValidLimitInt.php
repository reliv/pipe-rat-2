<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidLimit;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertValidLimitInt implements AssertValidLimit
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return void
     * @throws InvalidLimit
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $limit = $request->getAttribute(
            WithRequestAttributeLimit::ATTRIBUTE
        );

        if ($limit === null) {
            return;
        }

        if (!is_int($limit)) {
            throw new InvalidLimit(
                'Limit must be int, got: ' . $limit
            );
        }
    }
}
