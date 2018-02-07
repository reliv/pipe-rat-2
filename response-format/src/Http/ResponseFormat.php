<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\ResponseFormat\Api\IsRequestValidAcceptType;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormat extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_ACCEPTS = IsRequestValidAcceptType::OPTION_ACCEPTS;
    const OPTION_NOT_ACCEPTABLE_STATUS_CODE = 'not-acceptable-status-code';
    const OPTION_NOT_ACCEPTABLE_STATUS_MESSAGE = 'not-acceptable-status-message';

    const DEFAULT_ACCEPTS = [IsRequestValidAcceptType::ALL_TYPES];
    const DEFAULT_NOT_ALLOWED_STATUS_CODE = 406;
    const DEFAULT_NOT_ALLOWED_STATUS_MESSAGE = 'Not Acceptable: Accepts Format';

    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format';
    }

    protected $buildFailDataResponse;
    protected $isRequestValidAcceptType;
    protected $defaultAccepts;
    protected $defaultNotAcceptableStatusCode;
    protected $defaultNotAcceptableStatusMessage;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param IsRequestValidAcceptType           $isRequestValidAcceptType
     * @param BuildFailDataResponse              $buildFailDataResponse
     * @param array                              $defaultAccepts
     * @param string                             $defaultNotAcceptableStatusCode
     * @param string                             $defaultNotAcceptableStatusMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        IsRequestValidAcceptType $isRequestValidAcceptType,
        BuildFailDataResponse $buildFailDataResponse,
        array $defaultAccepts = self::DEFAULT_ACCEPTS,
        string $defaultNotAcceptableStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE,
        string $defaultNotAcceptableStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE
    ) {
        $this->buildFailDataResponse = $buildFailDataResponse;
        $this->isRequestValidAcceptType = $isRequestValidAcceptType;
        $this->defaultAccepts = $defaultAccepts;
        $this->defaultNotAcceptableStatusCode = $defaultNotAcceptableStatusCode;
        $this->defaultNotAcceptableStatusMessage = $defaultNotAcceptableStatusMessage;
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
     * @return ResponseInterface|DataResponseBasic
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if (!$this->isRequestValidAcceptType->__invoke($request, $options)) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_NOT_ACCEPTABLE_STATUS_CODE,
                $this->defaultNotAcceptableStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_NOT_ACCEPTABLE_STATUS_MESSAGE,
                $this->defaultNotAcceptableStatusMessage
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failMessage,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        /** @var WithFormattedResponse $withFormattedResponseApi */
        $withFormattedResponseApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            WithFormattedResponse::class
        );

        $withFormattedResponseOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        return $withFormattedResponseApi->__invoke(
            $request,
            $response,
            $withFormattedResponseOptions
        );
    }
}
