<?php

namespace App\MkyDirectives;


use Core\Interfaces\MkyDirectiveInterface;
use Core\MkyCompiler\MkyDirectives\Directive;

class TestDirective extends Directive implements MkyDirectiveInterface
{

    public function getFunctions()
    {
        return [
            'test' => [[$this, 'test']],
        ];
    }

    public function test($int)
    {
        return sprintf('%s = %s (%s + 5)', $this->getRealVariable($int), $int + 5, $int);
    }
}