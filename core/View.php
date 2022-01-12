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
     * @param bool $isModuleView
     * @return string|bool
     */
    public function render(string $view, array $params = [], bool $isModuleView = true)
    {
        try {
            $baseConfig = include ROOT . 'config/config.php';
            $config = array_merge(config('mkyEngine'), ($isModuleView ? config('module') : $baseConfig['module']));
            $mkyEngine = new MkyEngine($config);
            return $mkyEngine->view($view, $params);
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
