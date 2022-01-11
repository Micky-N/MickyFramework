<?php

namespace Core;

use Exception;
use Core\MkyCompiler\MkyEngine;

class View
{
    /**
     * Displays the rendering of the view on the site
     *
     * @param string $view
     * @param array $params
     * @return string|bool
     * @throws Exception
     */
    public function render(string $view, array $params = [])
    {
        try {
            $config = array_merge(config('mkyEngine'), config('module'));
            $mkyEngine = new MkyEngine($config);
            return $mkyEngine->view($view, $params);
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
