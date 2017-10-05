<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;

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
        return 'data-extractor';
    }

    /**
     * @var GetDataModel
     */
    protected $getDataModel;

    /**
     * @var ResponseWithDataBody
     */
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
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $response = $next($request);

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        /** @var Extract $extract */
        $extract = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Extract::class
        );

        $extractOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $dataModel = $this->getDataModel->__invoke(
            $response
        );

        if (!is_array($dataModel) && !is_object($dataModel)) {
            return $response;
        }

        $dataArray = $extract->__invoke(
            $request,
            $extractOptions
        );

        return $this->responseWithDataBody->__invoke($response, $dataArray);
    }
}
