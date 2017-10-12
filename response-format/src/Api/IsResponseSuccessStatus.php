<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsResponseSuccessStatus implements IsResponseSuccess
{
    const DEFAULT_SUCCESS_STATUS_CODES = [200];

    protected $defaultSuccessStatusCodes;

    /**
     * @param array $defaultSuccessStatusCodes
     */
    public function __construct(
        array $defaultSuccessStatusCodes = self::DEFAULT_SUCCESS_STATUS_CODES
    ) {
        $this->defaultSuccessStatusCodes = $defaultSuccessStatusCodes;
    }

    /**
     * @param ResponseInterface $response
     * @param array             $options
     *
     * @return bool
     */
    public function __invoke(
        ResponseInterface $response,
        array $options = []
    ): bool {
        $successStatusCodes = Options::get(
            $options,
            self::OPTION_SUCCESS_STATUS_CODES,
            $this->defaultSuccessStatusCodes
        );

        return in_array($response->getStatusCode(), $successStatusCodes);
    }
}
