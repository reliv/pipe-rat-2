<?php

namespace Reliv\PipeRat2\Core;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DataResponseBasic extends Response implements DataResponse
{
    /**
     * @var  null
     */
    protected $dataBody = null;

    /**
     * @param mixed       $dataBody
     * @param int         $status
     * @param array       $headers
     * @param string|null $reasonPhrase
     */
    public function __construct(
        $dataBody,
        int $status = 200,
        array $headers = [],
        string $reasonPhrase = null
    ) {
        $this->dataBody = $dataBody;

        if (!empty($reasonPhrase) && !array_key_exists('reason-phrase', $headers)) {
            $headers['reason-phrase'] = $reasonPhrase;
        }

        parent::__construct('php://memory', $status, $headers);
    }

    /**
     * withDataBody
     *
     * @param mixed $dataBody
     *
     * @return ResponseInterface
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
