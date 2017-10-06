<?php

namespace Reliv\PipeRat2\Acl;

use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Api\IsAllowedNone;
use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\AclMiddleware;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    IsAllowed::class => [
                        'class' => IsAllowedNone::class
                    ],
                    IsAllowedAny::class => [],
                    IsAllowedNone::class => [],
                    IsAllowedRcmUser::class => [
                        'arguments' => [
                            \RcmUser\Api\Acl\IsAllowed::class
                        ],
                    ],
                    AclMiddleware::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => AclMiddleware::DEFAULT_NOT_ALLOWED_STATUS_CODE],
                            ['literal' => AclMiddleware::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE],
                        ],
                    ],
                ],
            ],
        ];
    }
}
