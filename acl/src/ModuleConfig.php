<?php

namespace Reliv\PipeRat2\Acl;

use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Api\IsAllowedNone;
use Reliv\PipeRat2\Acl\Api\IsAllowedNotConfigured;
use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAclMiddleware;
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
                    IsAllowedNotConfigured::class => [],
                    IsAllowedRcmUser::class => [
                        'arguments' => [
                            \RcmUser\Api\Acl\IsAllowed::class
                        ],
                    ],
                    RequestAclMiddleware::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            ['literal' => RequestAclMiddleware::DEFAULT_NOT_ALLOWED_STATUS_CODE],
                            ['literal' => RequestAclMiddleware::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE],
                        ],
                    ],
                ],
            ],
        ];
    }
}
