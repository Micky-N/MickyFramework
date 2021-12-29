<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $model = ucfirst($cli['model']);
    $modelName = "App\\Models\\".$model;
    $modellower = strtolower($model);
    $name = ucfirst($cli['name']);
    $action = isset($cli['action']) ? strtoupper($cli['action']): null;
    $actionlower = $action ? strtolower($action) : null;
    if (!strpos($name, 'Voter')) {
        throw new Exception("Le voter $name doit avoir un suffix Voter.");
    }
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $name, $template);
    $template = str_replace('!modelName', "use $modelName;", $template);
    $template = str_replace('!modellower', "\$$modellower", $template);
    $template = str_replace('!model', $model, $template);
    $template = str_replace('!action', $action ? "const $action = '$actionlower';" : '', $template);
    if (file_exists("app/Voters/$name.php")) {
        throw new Exception("Le voter $name existe déjà.");
    }
    if (!is_dir("app/Voters")) {
        mkdir("app/Voters", 0777, true);
    }
    $voter = fopen("app/Voters/$name.php", "w") or die("Impossible d'ouvre le fichier $name !");
    $start = "<"."?"."php\n\n";
    fwrite($voter, $start.$template);
    $arr = _readLine("bootstrap/Provider.php");
    $votersLine = array_search("    'voters' => [", $arr);
    array_splice($arr, $votersLine + 1, 0, "\t    \App\Voters\\{$name}::class,");
    $arr = array_values($arr);
    $arr = implode("\n", $arr);
    $ptr = fopen("bootstrap/Provider.php", "w");
    fwrite($ptr, $arr);
    print("Le voter $name a été créé !");
}
