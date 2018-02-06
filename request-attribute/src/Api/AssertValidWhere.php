<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface AssertValidWhere
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return void
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    );
}
