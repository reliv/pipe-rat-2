<?php

namespace Reliv\PipeRat2\Core;

use Psr\Http\Message\ResponseInterface;
use Reliv\PipeRat2\Http\Response;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BasicDataResponse extends Response implements DataResponse
{
    /**
     * @var  null
     */
    protected $dataBody = null;

    /**
     * withDataBody
     *
     * @param mixed $dataBody
     *
     * @return \Psr\Http\Message\MessageInterface|ResponseInterface
     */
    public function withDataBody($dataBody)
    {
        $new = clone $this;
        $new->dataBody = $dataBody;

        return $new;
    }

    /**
     * getDataBody
     *
     * @return mixed
     */
    public function getDataBody()
    {
        return $this->dataBody;
    }
}
