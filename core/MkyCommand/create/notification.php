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
    if(!strpos($name, 'Notification')){
        throw new MkyCommandException("$name notification must be suffixed by Notification");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY . "/templates/$option." . MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!via', "'$via'", $template);
    $template = str_replace('!tovia', $tovia, $template);
    if(file_exists("app/Notifications/$name.php")){
        throw new MkyCommandException("$name notification already exist");
    }
    if(!is_dir("app/Notifications")){
        mkdir("app/Notifications", 0777, true);
    }
    $notification = fopen("app/Notifications/$name.php", "w") or die("Unable to open file $name");
    $start = "<" . "?" . "php\n\n";
    fwrite($notification, $start . $template);
    print("$name notification created");
}
