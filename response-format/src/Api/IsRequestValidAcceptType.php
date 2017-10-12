<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsRequestValidAcceptType
{
    const ALL_TYPES = '*/*';
    const OPTION_ACCEPTS = 'accepts';

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
