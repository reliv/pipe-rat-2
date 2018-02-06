<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidOrder;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface AssertValidOrder
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
    );
}
