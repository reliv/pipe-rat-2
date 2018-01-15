<?php

namespace Reliv\PipeRat2\RequestFormat\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsValidContentTypeBasic implements IsValidContentType
{
    const DEFAULT_VALID_CONTENT_TYPES = [self::ALL_TYPES];

    protected $defaultValidContentTypes;

    /**
     * @param array $defaultValidContentTypes
     */
    public function __construct(
        array $defaultValidContentTypes = self::DEFAULT_VALID_CONTENT_TYPES
    ) {
        $this->defaultValidContentTypes = $defaultValidContentTypes;
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
        $validContentTypes = Options::get(
            $options,
            self::OPTION_VALID_CONTENT_TYPES,
            $this->defaultValidContentTypes
        );

        // allow this for all check
        if (in_array(self::ALL_TYPES, $validContentTypes)) {
            return true;
        }

        $contentTypes = $request->getHeader('Content-Type');

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType, $validContentTypes)) {
                return true;
            }
        }

        return false;
    }
}
