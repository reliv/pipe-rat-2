<?php

namespace Reliv\PipeRat2\RequestFormat\Http;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class RequestFormatAbstract extends MiddlewareWithConfigOptionsAbstract
{
    const OPTION_VALID_CONTENT_TYPES = 'content-types';
    /**
     * @var array
     */
    protected $methodsWithBody
        = [
            'POST',
            'PUT',
            'PATCH'
        ];
    /**
     * @var array
     */
    protected $defaultContentTypes = [];

    /**
     * isValidMethod
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isValidMethod(ServerRequestInterface $request)
    {
        $method = $request->getMethod();

        return in_array($method, $this->methodsWithBody);
    }

    /**
     * isValidAcceptType
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isValidContentType(ServerRequestInterface $request)
    {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        $validContentTypes = Options::get(
            $options,
            self::OPTION_VALID_CONTENT_TYPES,
            $this->defaultContentTypes
        );

        // allow this for all check
        if (in_array('*/*', $validContentTypes)) {
            return true;
        }

        $contentTypes = $request->getHeader('Content-Type');

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType, $validContentTypes)) {
                return true;
            }
        }

        return false;
    }
}
