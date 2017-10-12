<?php

namespace Reliv\PipeRat2\RequestFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\DataError\Api\GetErrorArray;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestFormat\Api\IsValidContentType;
use Reliv\PipeRat2\RequestFormat\Api\IsValidRequestMethod;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBody;
use Reliv\PipeRat2\RequestFormat\Exception\RequestFormatDecodeFail;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestFormat extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_VALID_CONTENT_TYPES = IsValidContentType::OPTION_VALID_CONTENT_TYPES;
    const OPTION_NOT_ACCEPTABLE_STATUS_CODE = 'not-acceptable-status-code';
    const OPTION_NOT_ACCEPTABLE_STATUS_MESSAGE = 'not-acceptable-status-message';

    const DEFAULT_VALID_CONTENT_TYPES = [IsValidContentType::ALL_TYPES];
    const DEFAULT_NOT_ALLOWED_STATUS_CODE = 406;
    const DEFAULT_NOT_ALLOWED_STATUS_MESSAGE = 'Not Acceptable: Request Format';

    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-format';
    }

    protected $isValidContentType;
    protected $isValidRequestMethod;
    protected $defaultValidContentType;
    protected $defaultNotAcceptableStatusCode;
    protected $defaultNotAcceptableStatusMessage;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param IsValidContentType                 $isValidContentType
     * @param IsValidRequestMethod               $isValidRequestMethod
     * @param array                              $defaultValidContentType
     * @param string                             $defaultNotAcceptableStatusCode
     * @param string                             $defaultNotAcceptableStatusMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        IsValidContentType $isValidContentType,
        IsValidRequestMethod $isValidRequestMethod,
        array $defaultValidContentType = self::DEFAULT_VALID_CONTENT_TYPES,
        string $defaultNotAcceptableStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE,
        string $defaultNotAcceptableStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE
    ) {
        $this->isValidContentType = $isValidContentType;
        $this->isValidRequestMethod = $isValidRequestMethod;
        $this->defaultValidContentType = $defaultValidContentType;
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
     * @return mixed
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

        if (!$this->isValidRequestMethod->__invoke($request, $options)) {
            return $next($request, $response);
        }

        if (!$this->isValidContentType->__invoke($request, $options)) {
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

            return new DataResponseBasic(
                null,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        /** @var WithParsedBody $withParsedBodyApi */
        $withParsedBodyApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            WithParsedBody::class
        );

        $withParsedBodyOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        try {
            $request = $withParsedBodyApi->__invoke(
                $request,
                $response,
                $withParsedBodyOptions
            );
        } catch (RequestFormatDecodeFail $exception) {
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

            return new DataResponseBasic(
                GetErrorArray::invoke(
                    $exception->getMessage()
                ),
                $failStatusCode,
                [],
                $failMessage
            );
        }

        return $next($request, $response);
    }
}
