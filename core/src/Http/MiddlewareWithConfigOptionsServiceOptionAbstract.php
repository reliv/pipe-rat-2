<?php

namespace Reliv\PipeRat2\Core\Http;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class MiddlewareWithConfigOptionsServiceOptionAbstract implements MiddlewareWithConfigKey
{
    /**
     * @var GetOptions
     */
    protected $getOptions;

    /**
     * @var GetServiceFromConfigOptions
     */
    protected $getServiceFromConfigOptions;

    /**
     * @var GetServiceOptionsFromConfigOptions
     */
    protected $getServiceOptionsFromConfigOptions;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
    ) {
        $this->getOptions = $getOptions;
        $this->getServiceFromConfigOptions = $getServiceFromConfigOptions;
        $this->getServiceOptionsFromConfigOptions = $getServiceOptionsFromConfigOptions;
    }
}
