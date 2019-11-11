<?php

namespace Reliv\PipeRat2\Acl\Api;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Reliv\PipeRat2\Options\Options;

/**
 * @deprecated This will be removed eventully. Use the new ACL system instead.
 *
 * Class IsAllowedRcmUser
 * @package Reliv\PipeRat2\Acl\Api
 */
class IsAllowedRcmUser implements IsAllowed
{
    const OPTION_RESOURCE_ID = 'resourceId';
    const OPTION_PRIVILEGE = 'privilege';
    protected $requestContext;

    public function __construct(ContainerInterface $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    /**
     * @deprecated This will be removed eventully. Use the new ACL system instead.
     * @param ServerRequestInterface $request
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->requestContext->get(AssertIsAllowed::class);

        try {
            //Note that "legacy-global-admin-functionality" is temporary and will be removed eventually.
            $assertIsAllowed->__invoke(AclActions::EXECUTE, ['type' => 'legacy-global-admin-functionality']);

            return true;
        } catch (NotAllowedException $e) {
            return false;
        }
    }
}
