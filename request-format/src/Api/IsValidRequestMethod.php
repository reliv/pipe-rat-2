<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsValidRequestMethod
{
    const OPTION_REQUEST_METHODS_WITH_PARSED_BODY = 'request-methods-with-parsed-body';

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool;
}
