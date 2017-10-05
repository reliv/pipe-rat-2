<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormatJsonError extends ResponseFormatJson
{
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format-json-error';
    }

    /**
     * @param GetOptions            $getOptions
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     */
    public function __construct(
        GetOptions $getOptions,
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel
    ) {
        parent::__construct(
            $getOptions,
            $isResponseFormattable,
            $getDataModel
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $response = $next($request);

        if (!$this->isError($request, $response)) {
            return $response;
        }

        return parent::__invoke(
            $request,
            $response,
            function ($request) use ($response) {
                return $response;
            }
        );
    }
}
