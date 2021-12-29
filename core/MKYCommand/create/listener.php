<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $name = ucfirst($cli['name']);
    if (!strpos($name, 'Listener')) {
        throw new Exception("Le listener $name doit avoir un suffix Listener.");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    if (file_exists("app/Listeners/$name.php")) {
        throw new Exception("Le listener $name existe déjà.");
    }
    if (!is_dir("app/Listeners")) {
        mkdir("app/Listeners", 0777, true);
    }
    $listener = fopen("app/Listeners/$name.php", "w") or die("Impossible d'ouvrir le fichier $name!");
    $start = "<"."?"."php\n\n";
    fwrite($listener, $start.$template);
    print("Le listener $name a été créé !");
}
