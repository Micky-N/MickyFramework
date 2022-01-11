<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $path = isset($cli['path']) ? ucfirst($cli['path']) : null;
    $controllerName = ucfirst($cli['name']);
    $crud = isset($cli['crud']) ? file_get_contents(MickyCLI::BASE_MKY."/templates/controller/crud.".MickyCLI::EXTENSION) : null;
    $model = isset($cli['model']) ? $cli['model'] : null;
    if (!strpos($controllerName, 'Controllers')) {
        throw new MkyCommandException("$controllerName controller must have be suffixed by Controllers");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $controllerName, $template);
    $template = str_replace('!path', $path ? "\\".ucfirst($path) : '', $template);
    $template = str_replace('!model', $model ? "use ProductModule\\Models\\".ucfirst($model).";" : '', $template);
    $template = str_replace('!crud', $crud ? "\n".$crud : '', $template);
    if (file_exists("app/Http/Controllers/$path$controllerName.php")) {
        throw new MkyCommandException("$controllerName controller already exist");
    }
    if (!is_dir("app/Http/Controllers".($path ? "/".$path : ''))) {
        mkdir("app/Http/Controllers".($path ? "/".$path : ''), 0777, true); // true for recursive create
    }
    $controller = fopen("app/Http/Controllers/".($path ? "$path/" : '')."$controllerName.php", "w") or die("Unable to open file $controllerName");
    $start = "<"."?"."php\n\n";
    fwrite($controller, $start.$template);
    print("$controllerName controller created");
}
