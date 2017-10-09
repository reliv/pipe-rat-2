<?php

namespace Reliv\PipeRat2\Acl\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseError;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAclMiddleware extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_NOT_ALLOWED_STATUS_CODE = 'not-allowed-status-code';
    const OPTION_NOT_ALLOWED_STATUS_MESSAGE = 'not-allowed-status-message';

    const DEFAULT_NOT_ALLOWED_STATUS_CODE = 401;
    const DEFAULT_NOT_ALLOWED_STATUS_MESSAGE = 'Not Authorized';

    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-acl';
    }

    /**
     * @var int
     */
    protected $defaultFailStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE;

    /**
     * @var string
     */
    protected $defaultFailStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE;

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
        int $defaultFailStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE,
        string $defaultFailStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE
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

        /** @var IsAllowed $isAllowedApi */
        $isAllowedApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            IsAllowed::class
        );

        $isAllowedOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $isAllowed = $isAllowedApi->__invoke(
            $request,
            $isAllowedOptions
        );

        if (!$isAllowed) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_NOT_ALLOWED_STATUS_CODE,
                $this->defaultFailStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_NOT_ALLOWED_STATUS_MESSAGE,
                $this->defaultFailStatusMessage
            );

            return new DataResponseError(
                $failMessage,
                $failStatusCode
            );
        }

        return $next($request, $response);
    }
}
