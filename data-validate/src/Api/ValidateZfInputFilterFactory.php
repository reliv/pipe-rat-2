<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateZfInputFilterFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return ValidateZfInputFilter
     */
    public function __invoke(
        $serviceContainer
    ) {
        return new ValidateZfInputFilter(
            $serviceContainer,
            ValidateZfInputFilter::DEFAULT_PRIMARY_MESSAGE
        );
    }
}
