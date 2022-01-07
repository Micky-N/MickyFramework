<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $middlewareName = ucfirst($cli['name']);
    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
    $template = str_replace('!name', $middlewareName, $template);
    if (!strpos($middlewareName, 'Middleware')) {
        throw new MkyCommandException("$middlewareName middleware must be suffixed by Middleware");
    }
    if (file_exists("app/Http/Middlewares/$middlewareName.php")) {
        throw new MkyCommandException("$middlewareName middleware already exist");
    }
    if (!is_dir("app/Http/Middlewares")) {
        mkdir("app/Middlewares", 0777, true);
    }
    $middleware = fopen("app/Http/Middlewares/$middlewareName.php", "w") or die("Unable to open file $middlewareName");
    $start = "<"."?"."php\n\n";
    fwrite($middleware, $start.$template);
    $arr = _readLine(dirname(__DIR__)."/../bootstrap/MiddlewareServiceProvider.php");
    $middlewaresLine = array_keys(preg_grep("/'voters' => \[/i", $arr))[0];
    $subname = str_replace('middleware', '', strtolower($middlewareName));
    array_splice($arr, $middlewaresLine + 1, 0, "\t    '$subname' => \App\Http\Middlewares\\{$middlewareName}::class,");
    $arr = array_values($arr);
    $arr = implode("\n", $arr);
    $ptr = fopen(dirname(__DIR__)."/../bootstrap/MiddlewareServiceProvider.php", "w");
    fwrite($ptr, $arr);
    print("$middlewareName middleware created");
}
