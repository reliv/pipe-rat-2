<?php

namespace Reliv\PipeRat2\Core;

use Psr\Http\Message\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DataResponseError extends Response implements DataResponse
{
    /**
     * @var  null
     */
    protected $dataBody = null;

    /**
     * @param string $primaryMessage
     * @param array  $fieldMessages
     * @param int    $status
     * @param array  $headers
     */
    public function __construct(
        string $primaryMessage,
        int $status = 400,
        array $fieldMessages = [],
        array $headers = []
    ) {
        $this->dataBody = [
            'error' => $primaryMessage
        ];

        if (!empty($fieldMessages)) {
            $this->dataBody['fieldMessages'] = $fieldMessages;
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
