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
use Reliv\PipeRat2\Repository\Api\Count;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RepositoryCount extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_CRITERIA = 'criteria';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-count';
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

        /** @var Count $countApi */
        $countApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            Count::class
        );

        $countOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $where = $request->getAttribute(
            WithRequestAttributeWhere::ATTRIBUTE,
            []
        );

        $criteria = Options::get(
            $countOptions,
            self::OPTION_CRITERIA,
            $where
        );

        $result = $countApi->__invoke(
            $criteria,
            $countOptions
        );

        return new DataResponseBasic(
            $result
        );
    }
}
