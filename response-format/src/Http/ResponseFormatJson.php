<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormatJson extends ResponseFormatAbstract
{
    const OPTION_JSON_ENCODING_OPTIONS = 'jsonEncodeOptions';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format-json';
    }

    /**
     * @var array
     */
    protected $defaultAcceptTypes
        = [
            'application/json',
            'json'
        ];

    /**
     * @var IsResponseFormattable
     */
    protected $isResponseFormattable;

    /**
     * @var GetDataModel
     */
    protected $getDataModel;

    /**
     * @param GetOptions            $getOptions
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     */
    public function __construct(
        GetOptions $getOptions,
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        parent::__construct($getOptions);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if (!$this->isResponseFormattable->__invoke($response)) {
            return $response;
        }

        if (!$this->isValidAcceptType($request)) {
            return $response;
        }

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $jsonEncodeOptions = Options::get(
            $options,
            self::OPTION_JSON_ENCODING_OPTIONS,
            JSON_PRETTY_PRINT
        );

        $dataModel = $this->getDataModel->__invoke($response);

        $body = $response->getBody();
        $content = json_encode($dataModel, $jsonEncodeOptions);
        $err = json_last_error();
        if ($err !== JSON_ERROR_NONE) {
            throw new \Exception('json_encode failed to encode');
        }

        $body->rewind();
        $body->write($content);

        return $response->withBody($body)->withHeader(
            'Content-Type',
            'application/json'
        );
    }
}
