<?php

namespace Reliv\PipeRat2\DataExtractor\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseDataExtractor extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-data-extractor';
    }

    protected $getDataModel;
    protected $responseWithDataBody;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param GetDataModel                       $getDataModel
     * @param ResponseWithDataBody               $responseWithDataBody
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        GetDataModel $getDataModel,
        ResponseWithDataBody $responseWithDataBody
    ) {
        $this->getDataModel = $getDataModel;
        $this->responseWithDataBody = $responseWithDataBody;
        parent::__construct(
            $getOptions,
            $getServiceFromConfigOptions,
            $getServiceOptionsFromConfigOptions
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed|ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $response = $next($request, $response);

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        /** @var Extract $extract */
        $extract = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Extract::class
        );

        // Filtered field config
        $fieldConfig = $request->getAttribute(
            WithRequestAttributeExtractorFieldConfig::ATTRIBUTE,
            []
        );

        $dataModel = $this->getDataModel->__invoke(
            $response
        );

        $dataArray = $extract->__invoke(
            $dataModel,
            $fieldConfig
        );

        return $this->responseWithDataBody->__invoke(
            $response,
            $dataArray
        );
    }
}
