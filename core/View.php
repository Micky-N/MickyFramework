<?php

namespace Core;

class View
{
    public function render(string $view, array $params = [])
    {
        $layout = 'template';
        if(strpos($view, '/')){
            $array = explode('/', $view);
            $layout = $array[0];
            if(strpos($layout, '.') != -1){
                $layout = str_replace('.', '/', $layout);
            }
            $view = $array[1];
        }
        if(strpos($view, '.')){
            $view = str_replace('.', '/', $view);
        }
        try {
            ob_start();
            extract($params);

            include ROOT . "views/$view.php";

            $content = ob_get_clean();
            require_once ROOT . "views/layouts/$layout.php";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
