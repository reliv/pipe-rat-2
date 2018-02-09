<?php

namespace Reliv\PipeRat2\Core\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\DataResponse;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Json;
use Reliv\PipeRat2\Core\StatusCodes;
use Zend\Diactoros\Stream;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildFailDataResponseBasic implements BuildFailDataResponse
{
    const DEFAULT_ERROR_MESSAGE = 'An error occurred';

    /**
     * @param ServerRequestInterface $request
     * @param string                 $errorMessage
     * @param int                    $status
     * @param array                  $headers
     * @param string|null            $reasonPhrase
     * @param array                  $fieldMessages
     *
     * @return DataResponse
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $errorMessage,
        int $status = 200,
        array $headers = [],
        string $reasonPhrase = null,
        array $fieldMessages = []
    ): DataResponse {
        if (empty($reasonPhrase)) {
            $reasonPhrase = StatusCodes::getReasonPhrase(
                $status
            );
        }

        $dataBody = $this->buildDataBody($errorMessage, $fieldMessages);

        $response = new DataResponseBasic(
            $dataBody,
            $status,
            $headers,
            $reasonPhrase
        );

        $body = new Stream('php://temp', 'wb+');
        $body->write(Json::encode($dataBody));
        $body->rewind();

        // Build the body as JSON because these are often returned without being parsed
        return $response->withBody($body);
    }

    /**
     * @param string $errorMessage
     * @param array  $fieldMessages
     *
     * @return array
     * @throws \Exception
     */
    protected function buildDataBody(
        string $errorMessage,
        array $fieldMessages
    ) {
        if (empty($errorMessage)) {
            $errorMessage = static::DEFAULT_ERROR_MESSAGE;
        }
        $dataBody = [
            'error' => $errorMessage
        ];

        if (empty($fieldMessages)) {
            return $dataBody;
        }
        foreach ($fieldMessages as $fieldName => $message) {
            if (!is_string($fieldName) || !is_string($message)) {
                throw new \Exception(
                    "Field messages must be in format ['{field-name}' => '{user-friendly-message}']"
                );
            }
        }
        $dataBody['messages'] = $fieldMessages;

        return $dataBody;
    }
}
