<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class ResponseFormatAbstract extends MiddlewareWithConfigOptionsAbstract
{
    const OPTION_SUCCESS_STATUS_CODES = 'success-status-codes';
    const OPTION_ACCEPTS = 'accepts';

    /**
     * @var array
     */
    protected $defaultAcceptTypes = [];

    /**
     * @var array
     */
    protected $successStatusCodes
        = [
            200,
        ];

    /**
     * isError
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return bool
     */
    public function isError(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return !$this->isSuccess($request, $response);
    }

    /**
     * isSuccess
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return bool
     */
    public function isSuccess(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $successStatusCodes = Options::get(
            $options,
            self::OPTION_SUCCESS_STATUS_CODES,
            $this->successStatusCodes
        );

        return in_array($response->getStatusCode(), $successStatusCodes);
    }

    /**
     * isValidAcceptType
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isValidAcceptType(ServerRequestInterface $request)
    {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $validContentTypes = Options::get(
            $options,
            self::OPTION_ACCEPTS,
            $this->defaultAcceptTypes
        );

        // allow this for all check
        if (in_array('*/*', $validContentTypes)) {
            return true;
        }

        $contentTypes = $request->getHeader('Accept');

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType, $validContentTypes)) {
                return true;
            }
        }

        return false;
    }
}
