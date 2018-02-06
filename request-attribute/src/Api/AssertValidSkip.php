<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidSkip;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface AssertValidSkip
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return void
     * @throws InvalidSkip
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    );
}
