<?php

namespace Reliv\PipeRat2\DataFieldList\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeAllowedFieldConfigFromOptions implements WithRequestAttributeAllowedFieldConfig
{
    const OPTION_ALLOWED_FIELDS = 'allowed-fields';

    const DEFAULT_ALLOWED_FIELDS = [];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface {
        $allowedFields = Options::get(
            $options,
            self::OPTION_ALLOWED_FIELDS,
            self::DEFAULT_ALLOWED_FIELDS
        );

        return $request->withAttribute(self::ATTRIBUTE, $allowedFields);
    }
}
