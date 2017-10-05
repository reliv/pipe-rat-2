<?php

namespace Reliv\PipeRat2\Acl\Http;

use Reliv\PipeRat2\Core\Api\GetOptions;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class MiddlewareWithConfigOptionsAbstract implements MiddlewareWithConfigKey
{
    /**
     * @var GetOptions
     */
    protected $getOptions;

    /**
     * @param GetOptions $getOptions
     */
    public function __construct(
        GetOptions $getOptions
    ) {
        $this->getOptions = $getOptions;
    }
}
