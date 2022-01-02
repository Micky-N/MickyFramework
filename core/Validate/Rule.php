<?php

namespace Core\Validate;

use Closure;

class Rule
{

    private Closure $callback;
    private string $errorMessage;

    public function __construct(Closure $callback, string $errorMessage)
    {
        $this->callback = $callback;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Retourne la valeur du callback
     */ 
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Retourne la valeur de l'erreur
     */ 
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __get($key)
    {
        if(method_exists($this, 'get'.ucfirst($key))){
            return $this->{'get'.ucfirst($key)}();
        }
    }
}
