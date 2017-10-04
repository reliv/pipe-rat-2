<?php

namespace Reliv\PipeRat2\Repository\Api;

use Reliv\PipeRat2\DataHydrator\Api\Hydrate;

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
