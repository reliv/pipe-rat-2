<?php

namespace Reliv\PipeRat2\Repository\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Create extends MiddlewareWithConfigOptionsServiceOptionAbstract 
{
    const OPTION_CRITERIA = 'criteria';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'repository-create';
    }
{
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
    )
    {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        /** @var \Reliv\PipeRat2\Repository\Api\Create $createApi */
        $createApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            \Reliv\PipeRat2\Repository\Api\Create::class
        );

        $createOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );



        $createApi->__invoke(
            $criteria,
            $createOptions
        );
    }
}
