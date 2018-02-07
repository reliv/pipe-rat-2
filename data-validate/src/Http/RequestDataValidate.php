<?php

namespace Reliv\PipeRat2\DataValidate\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponse;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\DataValidate\Api\Validate;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestDataValidate extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_FAIL_STATUS_CODE = 'fail-status-code';
    const OPTION_FAIL_REASON = 'fail-reason';

    const DEFAULT_FAIL_STATUS_CODE = 400;
    const DEFAULT_FAIL_REASON = 'Bad Request: Data Invalid';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-data-validate';
    }

    protected $buildFailDataResponse;
    protected $defaultFailStatusCode;
    protected $defaultFailReason;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param int                                $defaultFailStatusCode
     * @param string                             $defaultFailReason
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        BuildFailDataResponse $buildFailDataResponse,
        int $defaultFailStatusCode = self::DEFAULT_FAIL_STATUS_CODE,
        string $defaultFailReason = self::DEFAULT_FAIL_REASON
    ) {
        $this->buildFailDataResponse = $buildFailDataResponse;
        $this->defaultFailStatusCode = $defaultFailStatusCode;
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
     * @return DataResponse
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

        /** @var Validate $validateApi */
        $validateApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Validate::class
        );

        $validateOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $data = $request->getParsedBody();

        $validateResult = $validateApi->__invoke(
            $data,
            $validateOptions
        );

        if (!$validateResult->isValid()) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_FAIL_STATUS_CODE,
                $this->defaultFailStatusCode
            );

            $failReason = Options::get(
                $options,
                self::OPTION_FAIL_REASON,
                $this->defaultFailReason
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failReason,
                $failStatusCode,
                [],
                $failReason
            );
        }

        return $next(
            $request->withParsedBody($validateResult->getValidData()),
            $response
        );
    }
}
