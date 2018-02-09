<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class XampleObject
{
    public $int = 42;
    public $string = 'string';
    public $null = null;
    public $bool = true;
    public $array = [];

    public function __toArray()
    {
        return get_object_vars($this);
    }
}
