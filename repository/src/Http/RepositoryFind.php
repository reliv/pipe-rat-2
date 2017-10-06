<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\DataResponseError;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\Repository\Api\Find;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeLimit;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeSkip;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributeWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryFind extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_CRITERIA = 'criteria';
    const OPTION_ORDER_BY = 'order-by';
    const OPTION_LIMIT = 'limit';
    const OPTION_OFFSET = 'offset';
    const OPTION_NOT_FOUND_STATUS_CODE = 'not-found-status-code';
    const OPTION_NOT_FOUND_STATUS_MESSAGE = 'not-found-status-message';

    const DEFAULT_NOT_FOUND_STATUS_CODE = 404;
    const DEFAULT_NOT_FOUND_MESSAGE = 'Not Found';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-find';
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

        /** @var Find $findApi */
        $findApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Find::class
        );

        $findOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $filterWhere = $request->getAttribute(
            RequestAttributeWhere::ATTRIBUTE,
            []
        );

        $filterOrder = $request->getAttribute(
            RequestAttributeOrder::ATTRIBUTE,
            null
        );

        $filterLimit = $request->getAttribute(
            RequestAttributeLimit::ATTRIBUTE,
            null
        );

        $filterSkip = $request->getAttribute(
            RequestAttributeSkip::ATTRIBUTE,
            null
        );

        $criteria = Options::get(
            $findOptions,
            self::OPTION_CRITERIA,
            $filterWhere
        );

        $orderBy = Options::get(
            $findOptions,
            self::OPTION_ORDER_BY,
            $filterOrder
        );

        $limit = Options::get(
            $findOptions,
            self::OPTION_LIMIT,
            $filterLimit
        );

        $offset = Options::get(
            $findOptions,
            self::OPTION_OFFSET,
            $filterSkip
        );

        $result = $findApi->__invoke(
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $findOptions
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

            return new DataResponseError(
                $failMessage,
                $failStatusCode
            );
        }

        return new DataResponseBasic(
            $result
        );
    }
}
