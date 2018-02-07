<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\Repository\Api\DeleteById;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryDeleteById extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_ID_PARAM = 'id-param-name';
    const OPTION_BAD_REQUEST_STATUS_CODE = 'bad-request-status-code';
    const OPTION_BAD_REQUEST_REASON_MISSING_ID = 'bad-request-reason-missing-id';
    const OPTION_BAD_REQUEST_REASON_FAILED = 'bad-request-reason-failed';

    const DEFAULT_ID_PARAM = 'id';
    const DEFAULT_BAD_REQUEST_STATUS_CODE = 400;
    const DEFAULT_BAD_REQUEST_REASON_MISSING_ID = 'Bad Request: Delete Requires ID';
    const DEFAULT_BAD_REQUEST_REASON_FAILED = 'Bad Request: Failed to Delete';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-delete-by-id';
    }

    protected $buildFailDataResponse;
    protected $defaultIdParam = self::DEFAULT_ID_PARAM;
    protected $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE;
    protected $defaultBadRequestReasonMissingId = self::DEFAULT_BAD_REQUEST_REASON_MISSING_ID;
    protected $defaultBadRequestReasonFailed = self::DEFAULT_BAD_REQUEST_REASON_FAILED;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param BuildFailDataResponse              $buildFailDataResponse
     * @param string                             $defaultIdParam
     * @param int                                $defaultBadRequestStatusCode
     * @param string                             $defaultBadRequestReasonMissingId
     * @param string                             $defaultBadRequestReasonFailed
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        BuildFailDataResponse $buildFailDataResponse,
        string $defaultIdParam = self::DEFAULT_ID_PARAM,
        int $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE,
        string $defaultBadRequestReasonMissingId = self::DEFAULT_BAD_REQUEST_REASON_MISSING_ID,
        string $defaultBadRequestReasonFailed = self::DEFAULT_BAD_REQUEST_REASON_FAILED
    ) {
        $this->buildFailDataResponse = $buildFailDataResponse;
        $this->defaultIdParam = $defaultIdParam;
        $this->defaultBadRequestStatusCode = $defaultBadRequestStatusCode;
        $this->defaultBadRequestReasonMissingId = $defaultBadRequestReasonMissingId;

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
     * @return ResponseInterface
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

        /** @var DeleteById $deleteApi */
        $deleteApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            DeleteById::class
        );

        $deleteOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $idParamName = Options::get(
            $options,
            self::OPTION_ID_PARAM,
            $this->defaultIdParam
        );

        $id = $request->getAttribute($idParamName);

        if (empty($id)) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_BAD_REQUEST_STATUS_CODE,
                $this->defaultBadRequestStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_BAD_REQUEST_REASON_MISSING_ID,
                $this->defaultBadRequestReasonMissingId
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failMessage,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        $result = $deleteApi->__invoke(
            $id,
            $deleteOptions
        );

        if ($result === false) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_BAD_REQUEST_STATUS_CODE,
                $this->defaultBadRequestStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_BAD_REQUEST_REASON_FAILED,
                $this->defaultBadRequestReasonFailed
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failMessage,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        return new DataResponseBasic(
            $result
        );
    }
}
