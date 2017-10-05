<?php

namespace Reliv\PipeRat2\Acl\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Response;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AclMiddleware extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'acl';
    }

    /**
     * @var int
     */
    protected $failStatusCode;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param int                                $failStatusCode
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        int $failStatusCode = 401
    ) {
        $this->failStatusCode = $failStatusCode;
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
            return new Response(
                'php://memory',
                $this->failStatusCode
            );
        }

        return $next($request, $response);
    }
}
