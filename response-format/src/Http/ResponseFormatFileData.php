<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetQueryParam;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormatFileData extends ResponseFormatAbstract
{
    const DOWNLOAD_HEADER = 'application/octet-stream';
    const OPTION_FILE_BASE_64_PROPERTY = 'fileBase64Property';
    const OPTION_FILE_CONTENT_TYPE_PROPERTY = 'fileContentTypeProperty';
    const OPTION_FILE_NAME_PROPERTY = 'fileNameProperty';

    const OPTION_DOWNLOAD_QUERY_PARAM = 'downloadQueryParam';
    const OPTION_FORCE_CONTENT_TYPE = 'forceContentType';
    const OPTION_FILE_NAME = 'fileName';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format-file-data';
    }

    /**
     * @var Extract
     */
    protected $extract;

    /**
     * @var GetQueryParam
     */
    protected $getQueryParam;

    /**
     * @var GetDataModel
     */
    protected $getDataModel;

    /**
     * @param GetOptions    $getOptions
     * @param GetQueryParam $getQueryParam
     * @param GetDataModel  $getDataModel
     * @param Extract       $extract
     */
    public function __construct(
        GetOptions $getOptions,
        GetQueryParam $getQueryParam,
        GetDataModel $getDataModel,
        Extract $extract
    ) {
        $this->getQueryParam = $getQueryParam;
        $this->extract = $extract;
        $this->getDataModel = $getDataModel;

        parent::__construct($getOptions);
    }

    /**
     * @var array
     */
    protected $defaultAcceptTypes = [];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $properties
     *
     * @return ResponseInterface
     */
    protected function getResponseWithContentType(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $properties
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

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

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return array
     * @throws \Exception
     */
    protected function getProperties(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $dataModel = $this->getDataModel->__invoke($response);

        $fileBase64Property = Options::get(
            $options,
            self::OPTION_FILE_BASE_64_PROPERTY
        );

        if (empty($fileBase64Property)) {
            throw new \Exception('FileDataResponseFormat requires fileBase64Property option to be set');
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

        return [
            'file' => base64_decode($this->getProperty($properties, $fileBase64Property)),
            'contentType' => $this->getProperty($properties, $fileContentTypeProperty, self::DOWNLOAD_HEADER),
            'fileName' => $this->getProperty($properties, $fileNameProperty),
        ];
    }

    /**
     * getProperty
     *
     * @param array  $list
     * @param string $key
     * @param null   $default
     *
     * @return null
     */
    protected function getProperty(array $list, $key, $default = null)
    {
        if (array_key_exists($key, $list)) {
            return $list[$key];
        }

        return $default;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed|ResponseInterface|string
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if (!$this->isValidAcceptType($request)) {
            return $response;
        }

        $properties = $this->getProperties($request, $response);

        if (!isset($properties['file'])) {
            return $response;
        }

        $body = $response->getBody();
        $body->write($properties['file']);

        $response = $response->withBody($body);

        $response = $this->getResponseWithContentType($request, $response, $properties);

        return $response;
    }
}
