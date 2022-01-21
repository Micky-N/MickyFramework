<?php

require_once 'vendor/autoload.php';

use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if(php_sapi_name() === "cli"){
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $name = ucfirst($cli['name']);
    $format = $cli['format'];
    $path = isset($cli['path']) ? ucfirst($cli['path']) : null;
    $namespace = sprintf("App\\MkyFormatters%s", $path ? "\\" . $path : '');
    if(!strpos($name, 'Formatter')){
        throw new MkyCommandException("$name must be suffixed by Formatter");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY . "/templates/$option." . MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!format', $format, $template);
    $template = str_replace('!path', $namespace, $template);

    $dir = sprintf("app/MkyFormatters%s", ($path ? "/" . $path : ''));
    if(file_exists("$dir/$name.php")){
        throw new MkyCommandException("$name formatter already exist");
    }
    if(!is_dir($dir)){
        mkdir($dir, 0777, true);
    }
    $formatter = fopen("$dir/$name.php", "w") or die("Unable to open file $name");
    $start = "<" . "?" . "php\n\n";
    fwrite($formatter, $start . $template);
    $mkyFormatterProviderFile = "app/Providers/MkyFormatterServiceProvider.php";
    $arr = _readLine(dirname(__DIR__) . "/../$mkyFormatterProviderFile");
    $formatterLine = array_keys(preg_grep("/return \[/i", $arr))[0];
    array_splice($arr, $formatterLine + 1, 0, "\tnew \\$namespace\\$name(),");
    $arr = array_values($arr);
    $arr = implode("\n", $arr);
    $ptr = fopen(dirname(__DIR__) . "/../$mkyFormatterProviderFile", "w");
    fwrite($ptr, $arr);
    print("$name formatter created");
}
