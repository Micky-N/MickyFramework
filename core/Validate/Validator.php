<?php

namespace Core\Validate;

use Core\Facades\Route;

class Validator
{

    private array $data = [];
    private array $rules = [];
    private array $errors = [];


    /**
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * @return bool
     */
    public function passed(): bool
    {
        $rules = new Rules($this->data, $this->rules);
        foreach ($this->data as $key => $d) {
            $rules->checkRule($key, $d);
        }
        if (!empty($rules->getErrors())) {
            $this->errors = $rules->getErrors();
            return false;
        }
        return true;
    }

    public static function check(array $data, array $rules)
    {
        $rules = new Rules($data, $rules);
        foreach ($data as $key => $d) {
            $data[$key] = $rules->checkRule($key, $d);
        }
        if (!empty($rules->getErrors())) {
            return Route::back()->withError($rules->getErrors());
        }
        return $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function __get($key)
    {
        if(property_exists($this, $key)){
            return $this->{'get'.ucfirst($key)}();
        }
    }
}
