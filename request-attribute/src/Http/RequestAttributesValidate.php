<?php

namespace Reliv\PipeRat2\RequestAttribute\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestValidAttributes;
use Reliv\PipeRat2\RequestAttribute\Exception\InvalidRequestAttribute;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributesValidate extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_BAD_REQUEST_STATUS_CODE = 'bad-request-status-code';
    const OPTION_BAD_REQUEST_STATUS_MESSAGE = 'bad-request-status-message';

    const DEFAULT_BAD_REQUEST_STATUS_CODE = 400;
    const DEFAULT_BAD_REQUEST_STATUS_MESSAGE = 'Bad Request';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-attributes-validate';
    }

    protected $defaultFailStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE;
    protected $defaultFailStatusMessage = self::DEFAULT_BAD_REQUEST_STATUS_MESSAGE;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param int                                $defaultFailStatusCode
     * @param string                             $defaultFailStatusMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        int $defaultFailStatusCode = self::DEFAULT_BAD_REQUEST_STATUS_CODE,
        string $defaultFailStatusMessage = self::DEFAULT_BAD_REQUEST_STATUS_MESSAGE
    ) {
        $this->defaultFailStatusCode = $defaultFailStatusCode;
        $this->defaultFailStatusMessage = $defaultFailStatusMessage;

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

        /** @var WithRequestValidAttributes $withRequestValidAttributesApi */
        $withRequestValidAttributesApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            WithRequestValidAttributes::class
        );

        $withRequestValidAttributesOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        try {
            $withRequestValidAttributesApi->__invoke(
                $request,
                $response,
                $withRequestValidAttributesOptions
            );
        } catch (InvalidRequestAttribute $exception) {
            return $this->getFailResponse(
                $exception,
                $options
            );
        }

        return $next($request, $response);
    }

    /**
     * @param InvalidRequestAttribute $exception
     * @param array                   $options
     *
     * @return DataResponseBasic
     */
    protected function getFailResponse(
        InvalidRequestAttribute $exception,
        array $options = []
    ) {
        $failStatusCode = Options::get(
            $options,
            self::OPTION_BAD_REQUEST_STATUS_CODE,
            $this->defaultFailStatusCode
        );

        $failMessage = Options::get(
            $options,
            self::DEFAULT_BAD_REQUEST_STATUS_MESSAGE,
            $this->defaultFailStatusMessage
        );

        return new DataResponseBasic(
            [
                'error' => $exception->getMessage()
                // . '(' . get_class($exception) . ')'
            ],
            $failStatusCode,
            [],
            $failMessage
        );
    }
}
