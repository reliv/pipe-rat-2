<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithFormattedResponseJson implements WithFormattedResponse
{
    const OPTION_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::OPTION_FORMATTABLE_RESPONSE_CLASSES;
    const OPTION_JSON_ENCODING_OPTIONS = 'json-encode-options';
    const OPTION_CONTENT_TYPE = 'content-type';

    const DEFAULT_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::DEFAULT_FORMATTABLE_RESPONSE_CLASSES;
    const DEFAULT_JSON_ENCODING_OPTIONS = 0;
    const DEFAULT_CONTENT_TYPE = 'application/json';

    protected $isResponseFormattable;
    protected $getDataModel;
    protected $defaultContentType = self::DEFAULT_CONTENT_TYPE;
    protected $defaultJsonEncodingOptions = self::DEFAULT_JSON_ENCODING_OPTIONS;
    protected $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES;

    /**
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     * @param string                $defaultContentType
     * @param string                $defaultJsonEncodingOptions
     * @param array                 $defaultFormattableResponseClasses
     */
    public function __construct(
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel,
        string $defaultContentType = self::DEFAULT_CONTENT_TYPE,
        string $defaultJsonEncodingOptions = self::DEFAULT_JSON_ENCODING_OPTIONS,
        array $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        $this->defaultContentType = $defaultContentType;
        $this->defaultJsonEncodingOptions = $defaultJsonEncodingOptions;
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

        $jsonEncodeOptions = Options::get(
            $options,
            self::OPTION_JSON_ENCODING_OPTIONS,
            $this->defaultJsonEncodingOptions
        );

        $dataModel = $this->getDataModel->__invoke($response);

        $body = $response->getBody();
        $content = Json::encode($dataModel, $jsonEncodeOptions);

        $body->rewind();
        $body->write($content);

        $contentType = Options::get(
            $options,
            self::OPTION_CONTENT_TYPE,
            $this->defaultContentType
        );

        return $response->withBody($body)->withHeader(
            'Content-Type',
            $contentType
        );
    }
}
