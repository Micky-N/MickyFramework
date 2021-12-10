<?php

namespace Core;

use Exception;
use Core\Facades\Template;

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
            extract(Template::_templateParams($params), EXTR_SKIP);
            $this->slashPath($view) ? require_once ROOT . "views/".$this->slashPath($view).".php" : false;
            extract(Template::_templateParams(), EXTR_SKIP);
            $this->slashPath(Template::getLayoutPath()) ? require(ROOT."views/".$this->slashPath(Template::getLayoutPath()).".php") : false;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function slashPath($view): string
    {
        if(!empty($view)){
            if (stripos($view, '.') !== false) {
                $view = str_replace('.', '/', $view);
            }
            if (!file_exists(ROOT . "views/$view.php")) {
                trigger_error("Le fichier $view n'existe pas.", E_USER_ERROR);
            }
            return $view;
        }
        return false;
    }
}
