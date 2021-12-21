<?php

namespace Core\Compiler;

use Closure;

class MkyDirective
{
    /**
     * @var string[]
     */
    private array $encode;
    /**
     * @var Closure[]
     */
    private array $callbacks;

    /**
     * @param string[] $encode
     * @param Closure[] $callbacks
     */
    public function __construct(array $encode, array $callbacks)
    {
        $this->encode = $encode;
        $this->callbacks = $callbacks;
    }

    /**
     * @return string[]
     */
    public function getEncode(): array
    {
        return $this->encode;
    }

    /**
     * @return Closure[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    public function __get($name)
    {
        if(property_exists($this, $name)){
            return $this->{'get'.ucfirst($name)}();
        }
    }
}