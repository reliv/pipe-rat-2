<?php

namespace Reliv\PipeRat2\Acl\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUser implements IsAllowed
{
    const OPTION_RESOURCE_ID = 'resourceId';
    const OPTION_PRIVILEGE = 'privilege';

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
        return false; //This was disabled in the ACL 2 project because it didn't follow new ACL rules.
    }
}
