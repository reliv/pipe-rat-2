<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeExtractorFieldConfigFromOptions implements WithRequestAttributeExtractorFieldConfig
{
    const OPTION_EXTRACTOR_FIELDS = 'extractor-fields';

    const DEFAULT_EXTRACTOR_FIELDS = [FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE];

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
        $extractorFieldConfig = Options::get(
            $options,
            self::OPTION_EXTRACTOR_FIELDS,
            self::DEFAULT_EXTRACTOR_FIELDS
        );

        return $request->withAttribute(self::ATTRIBUTE, $extractorFieldConfig);
    }

}
