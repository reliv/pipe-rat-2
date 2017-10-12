<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsResponseSuccess
{
    const OPTION_SUCCESS_STATUS_CODES = 'success-status-codes';

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return bool
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ): bool;
}
