<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidLimit;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface AssertValidLimit
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
    );
}