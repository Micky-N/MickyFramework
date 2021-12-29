<?php

require_once 'vendor/autoload.php';

use Core\MKYCommand\MickyCLI;

if(php_sapi_name() === "cli"){
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $via = $cli['via'];
    $tovia = 'to' . ucfirst($via);
    $name = ucfirst($cli['name']);
    if(!strpos($name, 'Notification')){
        throw new Exception("La notification $name doit avoir un suffix Notification.");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY . "/templates/$option." . MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!via', "'$via'", $template);
    $template = str_replace('!tovia', $tovia, $template);
    if(file_exists("app/Notifications/$name.php")){
        throw new Exception("La notification $name existe déjà.");
    }
    if(!is_dir("app/Notifications")){
        mkdir("app/Notifications", 0777, true);
    }
    $notification = fopen("app/Notifications/$name.php", "w") or die("Impossible d'ouvre le fichier $name!");
    $start = "<" . "?" . "php\n\n";
    fwrite($notification, $start . $template);
    print("La notification $name a été créé !");
}
