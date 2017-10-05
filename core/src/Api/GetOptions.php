<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetOptions
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $configKey
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $configKey
    ): array ;
}
