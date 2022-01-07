<?php

namespace Core\MkyCompiler;

use Closure;

class MkyDirective
{
    /**
     * @var string[]
     */
    private array $encodes;
    /**
     * @var Closure[]
     */
    private array $callbacks;

    /**
     * @param string[] $encodes
     * @param Closure[] $callbacks
     */
    public function __construct(array $encodes, array $callbacks)
    {
        $this->encodes = $encodes;
        $this->callbacks = $callbacks;
    }

    /**
     * Get all encodes
     *
     * @return string[]
     */
    public function getEncodes(): array
    {
        return $this->encodes;
    }

    /**
     * Get all callbacks
     *
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