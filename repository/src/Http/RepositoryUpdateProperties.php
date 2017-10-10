<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Core\TextResponse;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\Repository\Api\UpdateProperties;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryUpdateProperties extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_ID_PARAM = 'id-param-name';
    const OPTION_BAD_REQUEST_STATUS_CODE = 'bad-request-status-code';
    const OPTION_BAD_REQUEST_STATUS_MESSAGE = 'bad-request-status-message';
    const OPTION_NOT_FOUND_STATUS_CODE = 'not-found-status-code';
    const OPTION_NOT_FOUND_STATUS_MESSAGE = 'not-found-status-message';

    const DEFAULT_ID_PARAM = 'id';
    const DEFAULT_BAD_REQUEST_STATUS_CODE = 400;
    const DEFAULT_BAD_REQUEST_MESSAGE = 'Bad Request';
    const DEFAULT_NOT_FOUND_STATUS_CODE = 404;
    const DEFAULT_NOT_FOUND_MESSAGE = 'Not Found';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-update-properties';
    }

    protected $defaultIdParam = self::DEFAULT_ID_PARAM;
    protected $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE;
    protected $defaultBadRequestMessage = self::DEFAULT_BAD_REQUEST_MESSAGE;
    protected $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE;
    protected $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
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
        string $defaultIdParam = self::DEFAULT_ID_PARAM,
        int $defaultBadRequestStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE,
        string $defaultBadRequestMessage = self::DEFAULT_BAD_REQUEST_MESSAGE,
        int $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE,
        string $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE
    ) {
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

        $findByIdOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
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
                self::OPTION_BAD_REQUEST_STATUS_MESSAGE,
                $this->defaultBadRequestMessage
            );

            return new TextResponse(
                $failMessage,
                $failStatusCode
            );
        }

        $properties = $request->getParsedBody();

        $result = $updatePropertiesApi->__invoke(
            $id,
            $properties,
            $updatePropertiesOptions
        );

        return new DataResponseBasic(
            $result
        );
    }
}
