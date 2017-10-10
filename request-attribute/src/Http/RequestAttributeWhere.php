<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigKey;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RequestAttributeWhere extends MiddlewareWithConfigKey
{
    const ATTRIBUTE = 'pipe-rat-request-attribute-where-param';
    const OPTION_ALLOW_DEEP_WHERES = 'allow-deep-wheres';
}
