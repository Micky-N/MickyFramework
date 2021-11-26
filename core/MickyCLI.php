<?php

namespace Core;

use Exception;

class MickyCLI
{

    private $output = null;

    private $retval = 0;

    private $longOptions = [
        'create' => 'required',
        'name' => 'required',
        'pk' => 'required',
        'table' => 'required',
        'crud' => 'novalue',
        'request' => 'required',
        'controller' => 'required',
        'method' => 'required',
    ];

    private $shortOptions = [
        'n' => 'required',
        't' => 'required',
        'r' => 'required',
        'c' => 'required',
        'm' => 'required',
    ];

    private $required = [
        'create' => [
            'controller' => [
                'name' => 'required, n',
                'crud' => 'optional'
            ],
            'model' => [
                'name' => 'required, n',
                'pk' => 'optional',
                'table' => 'optional, t'
            ],
            'route' => [
                'request' => 'required, r',
                'controller' => 'required, c',
                'method' => 'required, m',
                'name' => 'optional, n'
            ]
        ],
    ];

    public function getAllOptions()
    {
        return array_merge($this->shortOptions, $this->longOptions);
    }

    public function getOption(string $option)
    {
        return $this->getAllOptions()[$option];
    }

    public function cliLongOptions()
    {
        $longOpt = array_map(function ($o) {
            switch ($this->longOptions[$o]) {
                case 'required':
                    return $o . ':';
                    break;
                case 'novalue':
                    return $o;
                    break;
                default:
                    break;
            }
        }, array_keys($this->longOptions));
        return $longOpt;
    }

    public function cliShortOptions()
    {
        $shortOpt = array_map(function ($o) {
            switch ($this->shortOptions[$o]) {
                case 'required':
                    return $o . ':';
                    break;
                case 'novalue':
                    return $o;
                    break;
                default:
                    break;
            }
        }, array_keys($this->shortOptions));
        return $shortOpt;
    }

    // DEBUT COMPILE

    public function start_mky()
    {
        return "<?php\n";
    }

    public function require_mky()
    {
        return "require_once '" . BASE_MKY . "./config.php'; \n
        require_once 'vendor/autoload.php'; \n";
    }

    public function compileExec(string $compile = '')
    {
        $exec = fopen(BASE_MKY . "/exec.php", "w");
        if ($compile) {
            $compile = start_mky() . require_mky() . $compile;
        }
        fwrite($exec, $compile ? $compile : "");
    }

    // FIN COMPILE


    public function getCommandBy(...$args)
    {
        $res = $this->commandList;
        foreach ($args as $key) {
            if ($this->isFieldExist($key)) {
                $res = $res[$key];
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

    public function isInputOptions(array $options)
    {
        if (!empty($options)) {
            return true;
        }
        throw new Exception("Saisir une option (--option).");
    }

    public function isInputMethod(array $options)
    {
        foreach ($options as $key => $value) {
            if ($value == false || !empty($value)) {
                return true;
            }
            throw new Exception("Methode $key invalide.");
        }
    }

    private function checkOptions(array $options)
    {
        $send = [];
        if ($this->isInputOptions($options)) {
            if ($this->isInputMethod($options)) {
                foreach ($options as $option => $method) {
                    if ($method != false && !empty($method)) {
                        $send[] = "--$option=$method";
                    } elseif ($method == false) {
                        $send[] = "--$option";
                    } else {
                        throw new Exception("Erreur de commande mky.");
                    }
                }
            }
        }
        return join(' ', $send);
    }

    public function executeMKY(array $options)
    {
        $script = $this->checkOptions($options);
        echo "\n-------------------------  Execution: php exec $script  ------------------------\n\n";
        exec("php " . BASE_MKY . "/exec.php $script", $this->output, $this->retval);
        echo (!empty($this->output[1]) ? $this->output[1] : $this->output[0]);
    }
}
