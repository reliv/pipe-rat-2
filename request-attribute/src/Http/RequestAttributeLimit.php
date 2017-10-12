<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigKey;

/**
 * @deprecated
 * @author James Jervis - https://github.com/jerv13
 */
interface RequestAttributeLimit extends MiddlewareWithConfigKey
{
    const ATTRIBUTE = 'pipe-rat-request-attribute-limit-param';

}
