<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithFormattedResponseFileData implements WithFormattedResponse
{
    const OPTION_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::OPTION_FORMATTABLE_RESPONSE_CLASSES;
    const OPTION_CONTENT_TYPE = 'content-type';
    const OPTION_FILE_BASE_64_PROPERTY = 'file-base-64-property';
    const OPTION_FILE_CONTENT_TYPE_PROPERTY = 'file-content-type-property';
    const OPTION_FILE_NAME_PROPERTY = 'file-name-property';
    const OPTION_DOWNLOAD_QUERY_PARAM = 'download-query-param';
    const OPTION_FORCE_DOWNLOAD = 'force-download';
    const OPTION_FORCE_DOWNLOAD_CONTENT_TYPE = 'force-download-content-type';
    const OPTION_FILE_NAME = 'file-name';

    const DEFAULT_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::DEFAULT_FORMATTABLE_RESPONSE_CLASSES;
    const DEFAULT_CONTENT_TYPE = 'application/octet-stream';
    const DEFAULT_FILE_BASE_64_PROPERTY = 'file';
    const DEFAULT_FILE_CONTENT_TYPE_PROPERTY = null; // 'fileType'
    const DEFAULT_FILE_NAME_PROPERTY = null; // 'fileName'
    const DEFAULT_DOWNLOAD_QUERY_PARAM = 'download';
    const DEFAULT_FORCE_DOWNLOAD = false;
    const DEFAULT_FILE_NAME = 'file';

    const DOWNLOAD_HEADER = 'application/octet-stream';

    protected $isResponseFormattable;
    protected $getDataModel;
    protected $extract;
    protected $defaultContentType = self::DEFAULT_CONTENT_TYPE;
    protected $defaultFileName = self::DEFAULT_FILE_NAME;
    protected $defaultFileBas64Property = self::DEFAULT_FILE_BASE_64_PROPERTY;
    protected $defaultFileContentTypeProperty = self::DEFAULT_FILE_CONTENT_TYPE_PROPERTY;
    protected $defaultFileNameProperty = self::DEFAULT_FILE_NAME_PROPERTY;
    protected $defaultDownloadQueryParam = self::DEFAULT_DOWNLOAD_QUERY_PARAM;
    protected $defaultForceDownload = self::DEFAULT_FORCE_DOWNLOAD;
    protected $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES;

    /**
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     * @param Extract               $extract
     * @param string                $defaultContentType
     * @param string                $defaultFileName
     * @param string                $defaultFileBas64Property
     * @param string                $defaultFileContentTypeProperty
     * @param string                $defaultFileNameProperty
     * @param string                $defaultDownloadQueryParam
     * @param string                $defaultForceDownload
     * @param array                 $defaultFormattableResponseClasses
     */
    public function __construct(
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel,
        Extract $extract,
        string $defaultContentType = self::DEFAULT_CONTENT_TYPE,
        string $defaultFileName = self::DEFAULT_FILE_NAME,
        string $defaultFileBas64Property = self::DEFAULT_FILE_BASE_64_PROPERTY,
        string $defaultFileContentTypeProperty = self::DEFAULT_FILE_CONTENT_TYPE_PROPERTY,
        string $defaultFileNameProperty = self::DEFAULT_FILE_NAME_PROPERTY,
        string $defaultDownloadQueryParam = self::DEFAULT_DOWNLOAD_QUERY_PARAM,
        string $defaultForceDownload = self::DEFAULT_FORCE_DOWNLOAD,
        array $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        $this->extract = $extract;
        $this->defaultContentType = $defaultContentType;
        $this->defaultFileName = $defaultFileName;
        $this->defaultFileBas64Property = $defaultFileBas64Property;
        $this->defaultFileContentTypeProperty = $defaultFileContentTypeProperty;
        $this->defaultFileNameProperty = $defaultFileNameProperty;
        $this->defaultDownloadQueryParam = $defaultDownloadQueryParam;
        $this->defaultForceDownload = $defaultForceDownload;
        $this->defaultFormattableResponseClasses = $defaultFormattableResponseClasses;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ResponseInterface {
        if (!$this->isResponseFormattable->__invoke($response, $options)) {
            // @todo ERROR?
            return $response;
        }

        $properties = $this->getProperties(
            $response,
            $options
        );

        $body = $response->getBody();
        $body->write($properties['file']);

        /** @var ResponseInterface $response */
        $response = $response->withBody($body);

        $contentType = Options::get(
            $options,
            self::OPTION_CONTENT_TYPE,
            $properties['contentType']
        );

        $isDownload = $this->isDownload(
            $request,
            $options,
            $contentType
        );

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

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     * @param                        $contentType
     *
     * @return bool
     */
    protected function isDownload(
        ServerRequestInterface $request,
        array $options,
        $contentType
    ) {
        $queryParams = $request->getQueryParams();

        $downloadQueryParam = Options::get(
            $options,
            self::OPTION_DOWNLOAD_QUERY_PARAM,
            $this->defaultDownloadQueryParam
        );

        $forceDownload = (bool)Options::get(
            $options,
            self::OPTION_FORCE_DOWNLOAD,
            $this->defaultForceDownload
        );

        $isDownload = (bool)Options::get(
            $queryParams,
            $downloadQueryParam,
            $forceDownload
        );

        return ($isDownload || $contentType === self::DOWNLOAD_HEADER);
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
            self::OPTION_FILE_BASE_64_PROPERTY,
            $this->defaultFileBas64Property
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
            self::OPTION_FILE_CONTENT_TYPE_PROPERTY,
            $this->defaultFileContentTypeProperty
        );

        if (!empty($fileContentTypeProperty)) {
            $propertyList[$fileContentTypeProperty] = true;
        }

        $fileNameProperty = Options::get(
            $options,
            self::OPTION_FILE_NAME_PROPERTY,
            $this->defaultFileNameProperty
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
            $this->defaultContentType
        );

        $fileName = Options::get(
            $properties,
            $fileNameProperty,
            $this->defaultFileName
        );

        return [
            'file' => base64_decode($fileBase64),
            'contentType' => $fileContentType,
            'fileName' => $fileName,
        ];
    }
}
