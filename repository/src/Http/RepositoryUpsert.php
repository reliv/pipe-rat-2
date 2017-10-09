<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Repository\Api\Upsert;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryUpsert extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-upsert';
    }

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
    ) {
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

        /** @var Upsert $upsertApi */
        $upsertApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Upsert::class
        );

        $upsertOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $data = $request->getParsedBody();

        $result = $upsertApi->__invoke(
            $data,
            $upsertOptions
        );

        return new DataResponseBasic(
            $result
        );
    }
}
