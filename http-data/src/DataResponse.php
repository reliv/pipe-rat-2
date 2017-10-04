<?php

namespace Reliv\PipeRat2\HttpData;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface DataResponse extends ResponseInterface
{
    /**
     * withDataBody
     *
     * @param mixed $data
     *
     * @return \Psr\Http\Message\MessageInterface|ResponseInterface
     */
    public function withDataBody($data);

    /**
     * getDataBody
     *
     * @return mixed
     */
    public function getDataBody();
}
