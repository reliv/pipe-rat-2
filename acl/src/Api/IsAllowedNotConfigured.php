<?php

namespace Reliv\PipeRat2\Acl\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedNotConfigured implements IsAllowed
{
    const OPTION_MESSAGE = 'message';

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $message = Options::get(
            $options,
            self::OPTION_MESSAGE,
            'Acl has not be configured'
        );

        throw new \Exception($message);
    }
}
