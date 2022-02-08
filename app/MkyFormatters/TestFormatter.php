<?php

namespace App\MkyFormatters;

use MkyEngine\Interfaces\MkyFormatterInterface;

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