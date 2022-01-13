<?php

require_once 'vendor/autoload.php';

use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if(php_sapi_name() === "cli"){
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $via = $cli['via'];
    $tovia = 'to' . ucfirst($via);
    $name = ucfirst($cli['name']);
    $module = isset($cli['module']) ? ucfirst($cli['module']) : null;
    $path = isset($cli['path']) ? ucfirst($cli['path']) : null;
    $namespace = sprintf("App%s\\Notifications%s", ($module ? "\\" . $module : ''), $path ? "\\" . $path : '');
    if(!strpos($name, 'Notification')){
        throw new MkyCommandException("$name notification must be suffixed by Notification");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY . "/templates/$option." . MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!path', $namespace, $template);
    $template = str_replace('!via', "'$via'", $template);
    $template = str_replace('!tovia', $tovia, $template);
    $dir = sprintf("app%s/Notifications%s", ($module ? '/' . $module : ''), ($path ? "/" . $path : ''));
    if(file_exists("$dir/$name.php")){
        throw new MkyCommandException("$name notification already exist");
    }
    if(!is_dir($dir)){
        mkdir($dir, 0777, true);
    }
    $notification = fopen("$dir/$name.php", "w") or die("Unable to open file $name");
    $start = "<" . "?" . "php\n\n";
    fwrite($notification, $start . $template);
    print("$name notification created");
}
