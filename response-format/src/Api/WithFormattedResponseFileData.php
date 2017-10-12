<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetQueryParam;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithFormattedResponseFileData implements WithFormattedResponse
{
    const OPTION_CONTENT_TYPE = 'content-type';
    const DOWNLOAD_HEADER = 'application/octet-stream';
    const OPTION_FILE_BASE_64_PROPERTY = 'fileBase64Property';
    const OPTION_FILE_CONTENT_TYPE_PROPERTY = 'fileContentTypeProperty';
    const OPTION_FILE_NAME_PROPERTY = 'fileNameProperty';

    const OPTION_DOWNLOAD_QUERY_PARAM = 'downloadQueryParam';
    const OPTION_FORCE_CONTENT_TYPE = 'forceContentType';
    const OPTION_FILE_NAME = 'fileName';

    const DEFAULT_CONTENT_TYPE = 'application/octet-stream';

    protected $isResponseFormattable;
    protected $getDataModel;
    protected $defaultContentType;

    /**
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetQueryParam         $getQueryParam
     * @param GetDataModel          $getDataModel
     * @param Extract               $extract
     * @param string                $defaultContentType
     */
    public function __construct(
        IsResponseFormattable $isResponseFormattable,
        GetQueryParam $getQueryParam,
        GetDataModel $getDataModel,
        Extract $extract,
        string $defaultContentType = self::DEFAULT_CONTENT_TYPE
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        $this->defaultContentType = $defaultContentType;
    }

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ): ResponseInterface
    {
        $properties = $this->getProperties(
            $response,
            $options
        );

        if (!isset($properties['file'])) {
            throw new \Exception(
                get_class($this) . ' requires file options to be set'
            );
        }

        $body = $response->getBody();
        $body->write($properties['file']);

        $response = $response->withBody($body);

        $downloadQueryParam = Options::get(
            $options,
            self::OPTION_DOWNLOAD_QUERY_PARAM,
            'download'
        );

        $isDownload = (bool)$this->getQueryParam->__invoke(
            $request,
            $downloadQueryParam,
            false
        );

        $contentType = Options::get(
            $options,
            self::OPTION_FORCE_CONTENT_TYPE,
            $properties['contentType']
        );

        $isDownload = ($isDownload || $contentType === self::DOWNLOAD_HEADER);

        if ($isDownload) {
            $contentType = self::DOWNLOAD_HEADER;
            $fileName = Options::get(
                $options,
                self::OPTION_FILE_NAME,
                $properties['fileName']
            );

            if (!empty($fileName)) {
                $response = $response->withHeader(
                    'Content-Disposition',
                    'attachment; filename="' . $fileName . '"'
                );
            }
        }

        return $response->withHeader(
            'Content-Type',
            $contentType
        );
    }

    protected function getQueryParam(
        ServerRequestInterface $request,
        $paramName,
        $default = null
    ) {
        $params = $request->getQueryParams();

        if (array_key_exists($paramName, $params)) {
            return $params[$paramName];
        }

        return $default;
    }

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return array
     * @throws \Exception
     */
    protected function getProperties(
        ResponseInterface $response,
        array $options = []
    ) {
        $dataModel = $this->getDataModel->__invoke($response);

        $fileBase64Property = Options::get(
            $options,
            self::OPTION_FILE_BASE_64_PROPERTY
        );

        if (empty($fileBase64Property)) {
            throw new \Exception(
                get_class($this) . ' requires ' . self::OPTION_FILE_BASE_64_PROPERTY . ' option to be set'
            );
        }

        $propertyList = [
            $fileBase64Property => true,
        ];

        $fileContentTypeProperty = Options::get(
            $options,
            self::OPTION_FILE_CONTENT_TYPE_PROPERTY
        );

        if (!empty($fileContentTypeProperty)) {
            $propertyList[$fileContentTypeProperty] = true;
        }

        $fileNameProperty = Options::get(
            $options,
            self::OPTION_FILE_NAME_PROPERTY
        );

        if (!empty($fileNameProperty)) {
            $propertyList[$fileNameProperty] = true;
        }

        $properties = $this->extract->__invoke(
            $dataModel,
            [
                Extract::OPTION_PROPERTY_LIST => $propertyList
            ]
        );

        $fileBase64 = Options::get(
            $properties,
            $fileBase64Property
        );

        $fileContentType = Options::get(
            $properties,
            $fileContentTypeProperty,
            $this->defaultContentType // self::DOWNLOAD_HEADER
        );

        $fileName = Options::get(
            $properties,
            $fileNameProperty
        );

        return [
            'file' => base64_decode($fileBase64),
            'contentType' => $fileContentType,
            'fileName' => $fileName,
        ];
    }
}
