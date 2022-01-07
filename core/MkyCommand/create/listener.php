<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $name = ucfirst($cli['name']);
    if (!strpos($name, 'Listener')) {
        throw new MkyCommandException("$name listener must be suffixed by Listener");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    if (file_exists("app/Listeners/$name.php")) {
        throw new MkyCommandException("$name listener already exist");
    }
    if (!is_dir("app/Listeners")) {
        mkdir("app/Listeners", 0777, true);
    }
    $listener = fopen("app/Listeners/$name.php", "w") or die("Unable to open folder $name");
    $start = "<"."?"."php\n\n";
    fwrite($listener, $start.$template);
    print("$name listener created");
}
