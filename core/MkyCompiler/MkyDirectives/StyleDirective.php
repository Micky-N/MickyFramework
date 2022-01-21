<?php

namespace Core\MkyCompiler\MkyDirectives;


use Core\Interfaces\MkyDirectiveInterface;

class StyleDirective implements MkyDirectiveInterface
{

    public function getFunctions()
    {
        return [
            'style' => [[$this, 'style'], [$this, 'endstyle']],
        ];
    }

    public function style(string $href = null)
    {
        if($href){
            return '<link rel="stylesheet" type="text/css" href=' . $href . '>';
        }
        return '<style>';
    }

    public function endstyle(string $href = null)
    {
        return '</style>';
    }
}