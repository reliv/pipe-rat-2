<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithFormattedResponseFile implements WithFormattedResponse
{
    const OPTION_CONTENT_TYPE = 'content-type';

    const DEFAULT_CONTENT_TYPE = 'application/octet-stream';

    protected $defaultContentType;

    /**
     * @param string $defaultContentType
     */
    public function __construct(
        string $defaultContentType = self::DEFAULT_CONTENT_TYPE
    ) {
        $this->defaultContentType = $defaultContentType;
    }

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ): ResponseInterface {
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
