<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Count
{
    /**
     * @param array $criteria
     * @param array $options
     *
     * @return int
     */
    public function __invoke(
        array $criteria = [],
        array $options = []
    ):int ;
}
