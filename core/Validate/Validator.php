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
        if(count($this->data) < 1 && count($this->rules) > 0){
            $this->errors['form'] = 'Le formulaire ne doit pas être vide';
            return false;
        }
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
        if(empty($data) && !empty($rules)){
            return Route::back()->withError(['form' => 'Le formulaire ne doit pas être vide']);
        }
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
}
