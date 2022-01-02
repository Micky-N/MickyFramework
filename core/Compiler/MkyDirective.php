<?php

namespace Core\Compiler;

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
     * Retourne les caractères encodés
     *
     * @return string[]
     */
    public function getEncodes(): array
    {
        return $this->encodes;
    }

    /**
     * Retourne les fonctions
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