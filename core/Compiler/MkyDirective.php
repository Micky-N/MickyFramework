<?php

namespace Core\Compiler;

use Closure;

class MkyDirective
{
    /**
     * @param string[] $encode
     * @param Closure[] $callbacks
     */
    public function __construct(array $encode, array $callbacks)
    {
        $this->encode = $encode;
        $this->callbacks = $callbacks;
    }
}