<?php

namespace Reliv\PipeRat\Middleware\RequestFormat;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Http\MiddlewareWithConfigOptionsAbstract;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class RequestFormatAbstract extends MiddlewareWithConfigOptionsAbstract
{
    /**
     * @var array
     */
    protected $methodsWithBody = [
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
        $options = $this->getOptions($request);

        $validContentTypes = $options->get(
            'contentTypes',
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
