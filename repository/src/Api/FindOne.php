<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindOne
{
    /**
     * @param array $criteria
     * @param null  $orderBy
     * @param array $options
     *
     * @return null|object
     */
    public function __invoke(
        array $criteria = [],
        $orderBy = null,
        array $options = []
    );
}
