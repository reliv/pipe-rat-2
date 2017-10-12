<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Core\DataResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsResponseFormattable
{
    const OPTION_FORMATTABLE_RESPONSE_CLASSES = 'formattable-response-classes';

    const DEFAULT_FORMATTABLE_RESPONSE_CLASSES = [DataResponse::class];

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return bool
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ):bool;
}
