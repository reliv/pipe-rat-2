<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Find
{
    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param array      $options
     *
     * @return array|null
     */
    public function __invoke(
        array $criteria = [],
        array $orderBy = null,
        $limit = null,
        $offset = null,
        array $options = []
    );
}
