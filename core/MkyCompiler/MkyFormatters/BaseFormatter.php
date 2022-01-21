<?php


namespace Core\MkyCompiler\MkyFormatters;


class BaseFormatter implements \Core\Interfaces\MkyFormatterInterface
{

    public function getFormats()
    {
        return [
            'euro' => [$this, 'euro']
        ];
    }

    public function euro($number, string $currency = '€')
    {
        return "$number $currency";
    }
}