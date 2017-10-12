<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsValidContentType
{
    const ALL_TYPES = '*/*';

    const OPTION_VALID_CONTENT_TYPES = 'valid-content-types';

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
