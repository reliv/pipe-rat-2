<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\Repository\Api\FindOne;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryFindOne extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_CRITERIA = 'criteria';
    const OPTION_ORDER_BY = 'order-by';
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
        return 'repository-delete-by-id';
    }

    protected $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE;
    protected $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param int                                $defaultNotFoundStatusCode
     * @param string                             $defaultNotFoundMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        int $defaultNotFoundStatusCode = self::DEFAULT_NOT_FOUND_STATUS_CODE,
        string $defaultNotFoundMessage = self::DEFAULT_NOT_FOUND_MESSAGE
    ) {
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

        /** @var FindOne $findOneApi */
        $findOneApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            FindOne::class
        );

        $findOneOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $filterWhere = $request->getAttribute(
            WithRequestAttributeWhere::ATTRIBUTE,
            []
        );

        $filterOrder = $request->getAttribute(
            WithRequestAttributeOrder::ATTRIBUTE,
            null
        );

        $criteria = Options::get(
            $findOneOptions,
            self::OPTION_CRITERIA,
            $filterWhere
        );

        $orderBy = Options::get(
            $findOneOptions,
            self::OPTION_ORDER_BY,
            $filterOrder
        );

        $result = $findOneApi->__invoke(
            $criteria,
            $orderBy,
            $findOneOptions
        );

        if (empty($result)) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_NOT_FOUND_STATUS_CODE,
                $this->defaultNotFoundStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_NOT_FOUND_STATUS_MESSAGE,
                $this->defaultNotFoundMessage
            );

            return new DataResponseBasic(
                null,
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
