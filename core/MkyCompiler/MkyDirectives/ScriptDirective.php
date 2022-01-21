<?php


namespace Core\MkyCompiler\MkyDirectives;


class ScriptDirective implements \Core\Interfaces\MkyDirectiveInterface
{

    public function getFunctions()
    {
        return [
            'script' => [[$this, 'script'], [$this, 'endscript']],
        ];
    }

    public function script(string $src = null)
    {
        if($src){
            return '<script type="text/javascript" src=' . $src . '></script>';
        }
        return '<script>';
    }

    public function endscript()
    {
        return '</script>';
    }
}