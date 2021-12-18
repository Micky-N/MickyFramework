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
     * Get the value of callback
     */ 
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Get the value of error
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
