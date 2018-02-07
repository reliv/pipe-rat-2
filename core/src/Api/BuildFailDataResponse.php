<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\DataResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BuildFailDataResponse
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $errorMessage
     * @param int                    $status
     * @param array                  $headers
     * @param string|null            $reasonPhrase
     * @param array                  $fieldMessages
     *
     * @return DataResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $errorMessage,
        int $status = 200,
        array $headers = [],
        string $reasonPhrase = null,
        array $fieldMessages = []
    ): DataResponse;
}
