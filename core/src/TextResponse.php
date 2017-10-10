<?php

namespace Reliv\PipeRat2\Core;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class TextResponse extends \Zend\Diactoros\Response\TextResponse
{
    /**
     * @param \Psr\Http\Message\StreamInterface|string $text
     * @param int                                      $status
     * @param array                                    $headers
     */
    public function __construct($text, $status = 200, array $headers = [])
    {
        parent::__construct($text, $status, $headers);
    }
}
