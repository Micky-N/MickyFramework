<?php

const BASE_MKY = 'MKYCommand';
const COMMANDS = [
    'create' => [
        'controller' => [
            'name' => 'required',
            'crud' => 'optional'
        ],
        'model' => [
            'name' => 'required',
            'pk' => 'optional',
            'table' => 'optional'
        ],
        'route' => [
            'request' => 'required',
            'controller' => 'required',
            'method' => 'required',
            'name' => 'optional'
        ]
    ],
];


const OPTIONS =  [
    'create' => 'required',
    'name' => 'required',
    'pk' => 'required',
    'table' => 'required',
    'crud' => 'novalue',
    'request' => 'required',
    'controller' => 'required',
    'method' => 'required',
    'name' => 'optional',
];

function allOptions()
{
    return getopt('', allKeyOptions());
}

function allKeyOptions()
{
    $longOpt = array_map(function ($o) {
        switch (OPTIONS[$o]) {
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
    }, array_keys(OPTIONS));
    return $longOpt;
}

// DEBUT COMPILE

function start_mky()
{
    return "<?php \n";
}

function require_mky()
{
    return "require_once '" . BASE_MKY . "./config.php'; \n";
}

function run_mky($compile)
{
    $var = start_mky();
    $var .= require_mky();
    $var .= $compile;
    return $var;
}

function compileExec(string $compile = '')
{
    $exec = fopen(BASE_MKY . "/exec.php", "w");
    fwrite($exec, $compile ? run_mky($compile) : "");
}

// FIN COMPILE


function getOptionMethods(string $option)
{
    if (IsValidOption($option)) {
        return COMMANDS[$option];
    }
}

function IsValidOption(string $option)
{
    if (in_array($option, array_keys(OPTIONS))) {
        return true;
    }
    throw new Exception("L'option $option n'existe.");
}

function isValidMethod(string $option, string $method)
{
    if ((allOptions()[$option] != 'novalue' && !empty($method))
        || (in_array($option, COMMANDS) && in_array($method, COMMANDS[$option]))
    ) {
        return true;
    }
    throw new Exception("La méthode --{$option}={$method} n'existe pas.");
}

function isInputOptions(array $options)
{
    if (!empty($options)) {
        return true;
    }
    throw new Exception("Saisir une option (--option).");
}

function isInputMethod(array $options)
{
    foreach ($options as $key => $value) {
        if ($value == false || !empty($value)) {
            return true;
        }
        throw new Exception("Methode $key invalide.");
    }
}

function executeMKY(array $options)
{
    $send = [];
    foreach ($options as $option => $method) {
        if (IsValidOption($option)) {
            if (isValidMethod($option, $method)) {
                if(!empty($method) || $method != false){
                    $send[] = "--$option=$method";
                }else{
                    $send[] = "--$option";
                }
            }
        }
    }
    $script = join(' ', $send);
    $output = null;
    $retval = 0;
    echo "\n-------------------------  Execution: php exec $script  ------------------------\n\n";
    exec("php " . BASE_MKY . "/exec.php $script", $output, $retval);
    echo(!empty($output[1]) ? $output[1] : $output[0]);
}
