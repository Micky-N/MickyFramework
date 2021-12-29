<?php

namespace Core\MKYCommand;

use Exception;

class MickyCLI
{

    const BASE_MKY = 'core/MKYCommand';

    const EXTENSION = 'temp';

    private array $cli = [];

    private array $output = [];

    private int $retval = 0;

    private static $longOptions = [
        'create' => 'required',
        'name' => 'required',
        'pk' => 'required',
        'table' => 'required',
        'crud' => 'novalue',
        'request' => 'required',
        'controller' => 'required',
        'method' => 'required',
        'model' => 'required',
        'route' => 'required',
        'api' => 'novalue',
        'url' => 'required',
        'routename' => 'optional',
        'middleware' => 'required',
        'show' => 'required',
        'routes' => 'required',
        'namespace' => 'required',
        'voter' => 'required',
        'action' => 'required',
        'path' => 'required',
        'permission' => 'required',
        'cache' => 'required',
        'clear' => 'novalue',
        'notification' => 'required',
        'via' => 'required',
        'event' => 'required',
        'listener' => 'required',
    ];

    private static array $required = [
        'create' => [
            'controller' => [
                'name' => 'required',
                'crud' => 'optional',
                'model' => 'optional',
                'path' => 'optional'
            ],
            'model' => [
                'name' => 'required',
                'pk' => 'optional',
                'table' => 'optional'
            ],
            'route' => [
                'request' => 'required',
                'url' => 'required',
                'controller' => 'required',
                'method' => 'required',
                'routename' => 'optional',
                'api' => 'optional',
                'middleware' => 'optional',
                'namespace' => 'required',
                'permission' => 'optional'
            ],
            'middleware' => [
                'name' => 'required',
            ],
            'voter' => [
                'name' => 'required',
                'model' => 'required',
                'action' => 'optional'
            ],
            'notification' => [
                'name' => 'required',
                'via' => 'required'
            ],
            'event' => [
                'name' => 'required'
            ],
            'listener' => [
                'name' => 'required'
            ]
        ],
        'show' => [
            'routes' => [
                'request' => 'required',
                'controller' => 'required'
            ]
        ],
        'cache' => [
            'clear' => [
                'path' => 'optional'
            ],
            'create' => [
                'path' => 'required',
            ]
        ]
    ];

    public function __construct(array $cli)
    {
        $this->cli = $cli;
    }

    public function getOption(string $option)
    {
        return self::$longOptions[$option];
    }

    public static function cliLongOptions()
    {
        $longOpt = array_map(function ($o) {
            switch (self::$longOptions[$o]) {
                case 'required':
                    return $o . ':';
                    break;
                case 'optional':
                    return $o . '::';
                    break;
                case 'novalue':
                    return $o;
                    break;
                default:
                    break;
            }
        }, array_keys(self::$longOptions));
        return $longOpt;
    }

    public function getCommandBy(...$args)
    {
        $res = self::$required;
        foreach ($args as $key) {
            if ($this->isFieldExist($key)) {
                $res = isset($res[$key]) ? $res[$key] : $key;
            }
        }
        return $res;
    }

    public function isFieldExist(string $option)
    {
        if (!empty($this->getOption($option))) {
            return true;
        }
    }

    public function getMethod(string $method, ...$options)
    {
        if ($this->getCommandBy($options, $method)) {
            return $this->getCommandBy($options, $method);
        }
        throw new Exception("La méthode {$method} n'existe pas.");
    }

    public function isInputCli()
    {
        if (!empty((array_keys($this->cli)))) {
            return true;
        }
        throw new Exception("Saisir une option (--option).");
    }

    public function isInputMethod()
    {
        foreach ($this->cli as $key => $value) {
            if ($value == false || !empty($value)) {
                return true;
            }
            throw new Exception("Methode $key invalide.");
        }
    }

    private function sendOptions()
    {
        $send = [];
        foreach ($this->cli as $cli => $option) {
            if ($option != false) {
                $send[] = "--$cli=$option";
            } elseif ($option == false) {
                $send[] = "--$cli";
            } else {
                throw new Exception("Erreur de commande mky.");
            }
        }
        return join(' ', $send);
    }

    private function checkCLI(string $cliKey)
    {
        $cliOption = $this->cli[$cliKey];
        unset($this->cli[$cliKey]);
        if (!empty(self::$required[$cliKey][$cliOption])) {
            if (!empty($this->cli)) {
                foreach ($this->cli as $cli => $option) {
                    $req = $this->getCommandBy($cliKey, $cliOption);
                    if (!isset($req[$cli])) {
                        unset($this->cli[$cli]);
                    } elseif ($req[$cli] == 'required' && empty($this->cli[$cli])) {
                        throw new Exception("La commande $cli est requie.");
                    }
                }
            }
        }
        $this->cli[$cliKey] = $cliOption;
        return true;
    }

    public function executeMKY()
    {
        $script = $this->sendOptions();
        exec("php " . self::BASE_MKY . "/exec.php $script", $this->output, $this->retval);
        echo "\n-------------------------  Execution: $script  ------------------------\n\n";
        echo join("\n", $this->output);
    }

    public function run()
    {
        $compile = '';
        if ($this->isInputCli() && 
        (array_key_exists('create', $this->cli) ||
        array_key_exists('show', $this->cli) ||
        array_key_exists('cache', $this->cli)
        )
        ) {
            foreach ($this->cli as $cli => $option) {
                if (array_key_exists($cli, self::$longOptions)) {
                    if (array_key_exists($cli, self::$required) && $this->checkCLI($cli) && $this->getCommandBy($cli, $option) != null) {
                        $compile = file_get_contents(self::BASE_MKY . "/$cli/$option.php");
                        break;
                    }
                } else {
                    throw new Exception("L'option $cli est invalide.");
                }
            }
            $this->compileExec($compile);
            $this->executeMKY();
            $this->compileExec();
        }
    }

    public function compileExec(string $compile = '')
    {
        $exec = fopen(self::BASE_MKY . "/exec.php", "w");
        fwrite($exec, $compile ?? "");
    }

    public static function table($data)
    {
        // Find longest string in each column
        $columns = [];
        foreach ($data as $row_key => $row) {
            foreach ($row as $cell_key => $cell) {
                $length = strlen($cell);
                if (empty($columns[$cell_key]) || $columns[$cell_key] || $length) {
                    $columns[$cell_key] = 20;
                }
            }
        }

        // Output table, padding columns
        $table = '';
        foreach ($data as $row_key => $row) {
            $table .= str_pad('', $columns[$cell_key] * count($row), '-') . PHP_EOL;
            foreach ($row as $cell_key => $cell) {
                $table .= "|" . str_pad($cell, $columns[$cell_key]);
            }
            $table .= PHP_EOL;
        }
        $table .= PHP_EOL . PHP_EOL;
        return $table;
    }
}
