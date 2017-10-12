<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Core\DataResponse;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsResponseFormattableBasic implements IsResponseFormattable
{
    protected $defaultFormattableResponseClasses;

    /**
     * @param array $defaultFormattableResponseClasses
     */
    public function __construct(
        array $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
    ) {
        $this->defaultFormattableResponseClasses = $defaultFormattableResponseClasses;
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
    ):bool {
        $formattableResponseClasses = Options::get(
            $options,
            self::OPTION_FORMATTABLE_RESPONSE_CLASSES,
            $this->defaultFormattableResponseClasses
        );

        foreach ($formattableResponseClasses as $formattableResponseClass) {
            if (is_a($response, $formattableResponseClass)) {
                return true;
            }
        }

        return false;
    }
}
