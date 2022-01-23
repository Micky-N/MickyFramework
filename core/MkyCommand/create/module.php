<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $routename = isset($cli['routename']) && $cli['routename'] !== false ? $cli['routename'].':' : '';
    $name = ucfirst($cli['name']);
    $path = "App\\$name";
    $folders = ['Events', 'Http', 'Listeners', 'Models', 'Notifications', 'Providers', 'routes', 'views', 'Voters'];
    $dir = sprintf("app/%s", $name);
    if(is_dir($dir)){
        throw new MkyCommandException("Module $name already exist");
    }
    foreach ($folders as $folder){
        if (!is_dir("$dir/$folder")) {
            mkdir("$dir/$folder", 0777, true);
        }
    }
    foreach (['Controllers', 'Middlewares'] as $folder){
        if (!is_dir("$dir/Http/$folder")) {
            mkdir("$dir/Http/$folder", 0777, true);
        }
    }

    $files = [
        'config' => ['app.php', $dir],
        'eventServiceProvider' => ['EventServiceProvider.php', "$dir/Providers"],
        'middlewareServiceProvider' => ['MiddlewareServiceProvider.php', "$dir/Providers"],
        'functions' => ['functions.php', "$dir/routes"],
    ];
    foreach ($files as $key => $file){
        $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option/$key.".MickyCLI::EXTENSION);
        $module = fopen("$file[1]/$file[0]", "w") or die("Unable to open folder $file[1]/$file[0]");
        $start = "<"."?"."php\n\n";
        fwrite($module, $start.$template);
    }

    $files = [
        'web' => ['web.yaml', "$dir/routes"],
        'admin' => ['admin.yaml', "$dir/routes"]
    ];
    foreach ($files as $key => $file){
        $module = fopen("$file[1]/$file[0]", "w") or die("Unable to open folder $file[1]/$file[0]");
        fwrite($module, $routename);
    }

    $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option/module.".MickyCLI::EXTENSION);
    $template = str_replace('!name', "{$name}Module", $template);
    $template = str_replace('!path', $path, $template);
    $module = fopen("$dir/{$name}Module.php", "w") or die("Unable to open folder {$name}Module");
    $start = "<"."?"."php\n\n";
    fwrite($module, $start.$template);

    print("Module $name created");
}