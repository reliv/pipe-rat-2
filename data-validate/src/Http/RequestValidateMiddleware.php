<?php

namespace Reliv\PipeRat2\DataValidate\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\DataValidate\Api\Validate;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestValidateMiddleware extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_FAIL_STATUS_CODE = 'fail-status-code';
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-data-validate';
    }

    /**
     * @var int
     */
    protected $defaultFailStatusCode;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param int                                $defaultFailStatusCode
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        int $defaultFailStatusCode = 400
    ) {
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

            return new DataResponseBasic(
                $validateResult,
                $failStatusCode
            );
        }

        return $next($request, $response);
    }
}
