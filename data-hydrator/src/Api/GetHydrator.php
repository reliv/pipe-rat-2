<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetHydrator
{
    /**
     * @param array $options
     *
     * @return Hydrate
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):Hydrate;
}
