<?php

namespace Reliv\PipeRat2\Core\Http;

use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\OptionsService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class MiddlewareWithConfigOptionsServiceOptionAbstract implements MiddlewareWithConfigKey
{
    const OPTION_SERVICE_NAME = OptionsService::SERVICE_NAME;
    const OPTION_SERVICE_OPTIONS = OptionsService::SERVICE_OPTIONS;

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
