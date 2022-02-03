<?php

namespace App\MkyFormatters;


use Core\Interfaces\MkyFormatterInterface;

class TestFormatter implements MkyFormatterInterface
{

    public function getFormats()
    {
        return [
            'test' => [$this, 'test'],
        ];
    }

    public function test($var)
    {
        return $var.' test';
    }
}