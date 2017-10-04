<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetEntityClass
{
    /**
     * @param array $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ):string;
}
