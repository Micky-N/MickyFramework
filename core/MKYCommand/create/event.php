<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $name = ucfirst($cli['name']);
    if (!strpos($name, 'Event')) {
        throw new Exception("L'event $name doit avoir un suffix Event.");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    if (file_exists("app/Events/$name.php")) {
        throw new Exception("L'event $name existe déjà.");
    }
    if (!is_dir("app/Events")) {
        mkdir("app/Events", 0777, true);
    }
    $event = fopen("app/Events/$name.php", "w") or die("Impossible d'ouvre le fichier $name!");
    $start = "<"."?"."php\n\n";
    fwrite($event, $start.$template);
    print("L'event $name a été créé !");
}
