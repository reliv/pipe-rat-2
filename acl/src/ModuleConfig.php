<?php

namespace Reliv\PipeRat2\Acl;

use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Api\IsAllowedNone;
use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;

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
                ],
            ],
        ];
    }
}
