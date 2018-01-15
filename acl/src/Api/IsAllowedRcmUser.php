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
     * @var \RcmUser\Api\Acl\IsAllowed
     */
    protected $isAllowed;

    /**
     * @param \RcmUser\Api\Acl\IsAllowed $isAllowed
     */
    public function __construct(
        \RcmUser\Api\Acl\IsAllowed $isAllowed
    ) {
        $this->isAllowed = $isAllowed;
    }

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
        $resourceId = Options::get(
            $options,
            self::OPTION_RESOURCE_ID
        );

        if (empty($resourceId)) {
            throw new \Exception('ResourceId is required');
        }

        $privilege = Options::get(
            $options,
            self::OPTION_PRIVILEGE,
            null
        );

        return $this->isAllowed->__invoke(
            $request,
            $resourceId,
            $privilege
        );
    }
}
