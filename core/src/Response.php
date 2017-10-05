<?php

namespace Reliv\PipeRat2\Core;

use Psr\Http\Message\StreamInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Response extends \Zend\Diactoros\Response
{
    /**
     * @param string|resource|StreamInterface $body
     * @param int                             $status
     * @param array                           $headers
     */
    public function __construct($body = 'php://memory', int $status = 200, array $headers = [])
    {
        parent::__construct($body, $status, $headers);
    }
}
