<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsRequestValidAcceptTypeBasic implements IsRequestValidAcceptType
{
    const DEFAULT_ACCEPTS = [self::ALL_TYPES];

    protected $defaultAcceptTypes;

    /**
     * @param array $defaultAcceptTypes
     */
    public function __construct(
        array $defaultAcceptTypes = self::DEFAULT_ACCEPTS
    ) {
        $this->defaultAcceptTypes = $defaultAcceptTypes;
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
        $validAcceptTypes = Options::get(
            $options,
            self::OPTION_ACCEPTS,
            $this->defaultAcceptTypes
        );

        // allow this for all check
        if (in_array(static::ALL_TYPES, $validAcceptTypes)) {
            return true;
        }

        $contentTypes = $request->getHeader('Accept');

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType, $validAcceptTypes)) {
                return true;
            }
        }

        return false;
    }
}
