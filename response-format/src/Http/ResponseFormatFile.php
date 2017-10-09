<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormatFile extends ResponseFormatAbstract
{
    const OPTION_CONTENT_TYPE = 'contentType';
    const DEFAULT_CONTENT_TYPE = 'application/octet-stream';

    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format-file';
    }

    /**
     * @param GetOptions $getOptions
     */
    public function __construct(GetOptions $getOptions)
    {
        parent::__construct($getOptions);
    }

    /**
     * @var array
     */
    protected $defaultAcceptTypes = [];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if (!$this->isValidAcceptType($request)) {
            return $response;
        }

        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $contentType = Options::get(
            $options,
            self::OPTION_CONTENT_TYPE,
            self::DEFAULT_CONTENT_TYPE
        );

        return $response->withHeader(
            'Content-Type',
            $contentType
        );
    }
}
