<?php

namespace Core\Validate;

use Core\Facades\Route;
use Core\Router;
use Exception;

class Validator
{

    private array $data;
    private array $rules;
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
     * Lance la vérification de chaque point de contrôle
     * mode instancier
     *
     * @return bool
     * @throws Exception
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

    /**
     * Lance la vérification de chaque point de contrôle
     * mode static avec redirection en arriere
     * avec les erreurs si présent
     *
     * @param array $data
     * @param array $rules
     * @return array|Router
     * @throws Exception
     */
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

    /**
     * Retourne les données du formulaire
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Retourne les règles saisie
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Retourne les erreurs
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function __get($key)
    {
        if(method_exists($this, "get".ucfirst($key))){
            return $this->{'get'.ucfirst($key)}();
        }
        throw new Exception("la variable $key n'existe pas");
    }
}
