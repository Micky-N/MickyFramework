<?php

namespace Core;

use Exception;
use Core\Compiler\MkyEngine;

class View
{
    /**
     * @param string $view
     * @param array $params
     * @return string|bool
     * @throws Exception
     */
    public function render(string $view, array $params = [])
    {
        try {
            $mkyengine = new MkyEngine(config('mkyengine'));
            return $mkyengine->view($view, $params);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
