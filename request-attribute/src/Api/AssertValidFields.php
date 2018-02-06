<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidFields;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface AssertValidFields
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
    );
}
