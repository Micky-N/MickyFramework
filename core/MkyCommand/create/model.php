<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $options = $cli['create'];
    $name = ucfirst($cli['name']);
    $table = !empty($cli['table']) ? $cli['table'] : null;
    $pk = !empty($cli['pk']) ? $cli['pk'] : null;
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$options.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!table', $table ? "protected string \$table = '$table';\n\t" : '' , $template);
    $template = str_replace('!pk', $pk ? "protected string \$primaryKey = '$pk';" : '', $template);
    if (file_exists("app/Models/$name.php")) {
        throw new MkyCommandException("$name model already exist");
    }
    if (!is_dir("app/Models")) {
        mkdir("app/Models", 0777, true); // true for recursive create
    }
    $model = fopen("app/Models/$name.php", "w") or die("Unable to open file $name");
    $start = "<"."?"."php\n\n";
    fwrite($model, $start.$template);
    print("$name model created");
}
