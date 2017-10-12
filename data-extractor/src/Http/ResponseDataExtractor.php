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
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;

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

        $extractOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $extractOptions[Extract::OPTION_PROPERTY_LIST] = $this->buildPropertyListOption(
            $request,
            $extractOptions
        );

        $dataModel = $this->getDataModel->__invoke(
            $response
        );

        if (!is_array($dataModel) && !is_object($dataModel)) {
            return $response;
        }

        $dataArray = $extract->__invoke(
            $dataModel,
            $extractOptions
        );

        return $this->responseWithDataBody->__invoke($response, $dataArray);
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $extractOptions
     *
     * @return array|null
     */
    public function buildPropertyListOption(
        ServerRequestInterface $request,
        array $extractOptions
    ) {
        $requestedFields = $request->getAttribute(
            WithRequestAttributeFields::ATTRIBUTE,
            null
        );

        $extractorPropertyList = Options::get(
            $extractOptions,
            Extract::OPTION_PROPERTY_LIST,
            null
        );

        // Nothing to be done
        if ($requestedFields === null && $extractorPropertyList === null) {
            return null;
        }

        if ($extractorPropertyList === null && is_array($requestedFields)) {
            return $requestedFields;
        }

        if ($requestedFields === null && is_array($extractorPropertyList)) {
            return $extractorPropertyList;
        }

        if (!is_array($requestedFields)) {
            $requestedFields = $extractorPropertyList;
        }

        return $this->buildPropertyList(
            $extractorPropertyList,
            $requestedFields
        );
    }

    /**
     * @param array $extractorPropertyList
     * @param array $requestedFields
     * @param array $list
     *
     * @return array
     */
    protected function buildPropertyList(
        array $extractorPropertyList,
        array $requestedFields,
        array &$list = []
    ) {
        foreach ($requestedFields as $filterProperty => $value) {
            // If it is not set in default, we ignore
            if (!array_key_exists($filterProperty, $extractorPropertyList)) {
                continue;
            }

            // If it is set false in default, we ignore
            if ($extractorPropertyList[$filterProperty] === false) {
                continue;
            }

            // We can turn them off if they are disabled
            if ($extractorPropertyList[$filterProperty] === true) {
                $list[$filterProperty] = (bool)$requestedFields[$filterProperty];
                continue;
            }

            // If they are arrays, then we check sub values
            if (is_array($extractorPropertyList[$filterProperty]) && is_array($value)) {
                $this->buildPropertyList(
                    $extractorPropertyList[$filterProperty],
                    $requestedFields[$filterProperty],
                    $list[$filterProperty]
                );
                continue;
            }
        }

        return $list;
    }
}
