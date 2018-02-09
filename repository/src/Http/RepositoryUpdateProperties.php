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
use Reliv\PipeRat2\Repository\Api\UpdateProperties;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryUpdateProperties extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_ID_PARAM = 'id-param-name';
    const OPTION_BAD_REQUEST_STATUS_CODE = 'bad-request-status-code';
    const OPTION_BAD_REQUEST_REASON_MISSING_ID = 'bad-request-reason-missing-id';
    const OPTION_NOT_FOUND_STATUS_CODE = 'not-found-status-code';
    const OPTION_NOT_FOUND_STATUS_MESSAGE = 'not-found-status-message';

    const DEFAULT_ID_PARAM = 'id';
    const DEFAULT_BAD_REQUEST_STATUS_CODE = 400;
    const DEFAULT_BAD_REQUEST_REASON_MISSING_ID = 'Bad Request: Update Properties Requires ID';
    const DEFAULT_NOT_FOUND_STATUS_CODE = 404;
    const DEFAULT_NOT_FOUND_MESSAGE = 'Not Found';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-update-properties';
    }

    protected $buildFailDataResponse;
    protected $defaultIdParam = self::DEFAULT_ID_PARAM;
    protected $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE;
    protected $defaultBadRequestMessage = self::DEFAULT_BAD_REQUEST_REASON_MISSING_ID;
    protected $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE;
    protected $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param BuildFailDataResponse              $buildFailDataResponse
     * @param string                             $defaultIdParam
     * @param int                                $defaultBadRequestStatusCode
     * @param string                             $defaultBadRequestMessage
     * @param int                                $defaultNotFoundStatusCode
     * @param string                             $defaultNotFoundMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        BuildFailDataResponse $buildFailDataResponse,
        string $defaultIdParam = self::DEFAULT_ID_PARAM,
        int $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE,
        string $defaultBadRequestMessage = self::DEFAULT_BAD_REQUEST_REASON_MISSING_ID,
        int $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE,
        string $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE
    ) {
        $this->buildFailDataResponse = $buildFailDataResponse;
        $this->defaultIdParam = $defaultIdParam;
        $this->defaultBadRequestStatusCode = $defaultBadRequestStatusCode;
        $this->defaultBadRequestMessage = $defaultBadRequestMessage;
        $this->defaultNotFoundStatusCode = $defaultNotFoundStatusCode;
        $this->defaultNotFoundMessage = $defaultNotFoundMessage;

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

        /** @var UpdateProperties $updatePropertiesApi */
        $updatePropertiesApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            UpdateProperties::class
        );

        $updatePropertiesOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
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
                $this->defaultBadRequestMessage
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failMessage,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        $properties = $request->getParsedBody();

        $result = $updatePropertiesApi->__invoke(
            $id,
            $properties,
            $updatePropertiesOptions
        );

        // @todo What if not found

        return new DataResponseBasic(
            $result
        );
    }
}
