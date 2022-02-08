<?php

namespace App\MkyDirectives;


use MkyEngine\Interfaces\MkyDirectiveInterface;
use MkyEngine\MkyEngine;

class TestDirective implements MkyDirectiveInterface
{

    public function getFunctions()
    {
        return [
            'test' => [$this, 'test'],
        ];
    }

    public function test($int)
    {
        return sprintf('%s = %s (%s + 5)', MkyEngine::getRealVariable($int), $int + 5, $int);
    }
}