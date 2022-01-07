<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $name = ucfirst($cli['name']);
    if (!strpos($name, 'Event')) {
        throw new MkyCommandException("$name event must be suffixed by Event");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    if (file_exists("app/Events/$name.php")) {
        throw new MkyCommandException("$name event already exist");
    }
    if (!is_dir("app/Events")) {
        mkdir("app/Events", 0777, true);
    }
    $event = fopen("app/Events/$name.php", "w") or die("Unable to open file $name");
    $start = "<"."?"."php\n\n";
    fwrite($event, $start.$template);
    print(" $name event created");
}
