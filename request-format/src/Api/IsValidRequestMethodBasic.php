<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsValidRequestMethodBasic implements IsValidRequestMethod
{
    const DEFAULT_REQUEST_METHODS_WITH_PARSED_BODY
        = [
            'POST',
            'PUT',
            'PATCH'
        ];

    protected $defaultRequestMethodsWithParsedBody = self::DEFAULT_REQUEST_METHODS_WITH_PARSED_BODY;

    /**
     * @param array $defaultRequestMethodsWithParsedBody
     */
    public function __construct(
        array $defaultRequestMethodsWithParsedBody = self::DEFAULT_REQUEST_METHODS_WITH_PARSED_BODY
    ) {
        $this->defaultRequestMethodsWithParsedBody = $defaultRequestMethodsWithParsedBody;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $method = $request->getMethod();

        $requestMethodsWithParsedBody = Options::get(
            $options,
            self::OPTION_REQUEST_METHODS_WITH_PARSED_BODY,
            $this->defaultRequestMethodsWithParsedBody
        );

        return in_array($method, $requestMethodsWithParsedBody);
    }
}
