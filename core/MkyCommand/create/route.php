<?php

require_once 'vendor/autoload.php';
use Core\MKYCommand\MickyCLI;
use Core\MkyCommand\MkyCommandException;

if (php_sapi_name() === "cli") {
    $cli = getopt('', MickyCLI::cliLongOptions());
    $option = $cli['create'];
    $crud = isset($cli['crud']) ? $cli['crud'] : null;
    $template = '';
    $request = $cli['request'];
    if($request != 'crud'){
        if(!in_array(strtoupper($request), ['GET', 'POST'])){
            throw new MkyCommandException("Request method must be GET or POST");
        }
        $url = $cli['url'];
        $controller = $cli['controller'];
        if(stripos($controller, 'Controller') === false){
            $controller .= 'Controller';
        }
        $method = $cli['method'];
        $routename = !empty($cli['routename']) ? $cli['routename'] : null;
        $permission = isset($cli['permission']) ? "can:".$cli['permission'] : null;
        $middleware = !empty($cli['middleware']) ? $cli['middleware'] : null;
        if(isset($cli['routename']) && $routename == null){
            $plural = json_decode(file_get_contents('lang/plural_word.json'), true);
            $name = str_replace('Controller', '', $controller);
            if(isset($plural[strtolower($name)])){
                $routename = $cli['routename'] == false ? $plural[strtolower($name)].".".$method : $routename;
            }
        }
        $template = file_get_contents(MickyCLI::BASE_MKY."/templates/$option.".MickyCLI::EXTENSION);
        $template = str_replace('!request', $request, $template);
        $template = str_replace('!url', "'$url'", $template);
        $template = str_replace('!controller', "\App\\Http\\Controllers\\".$controller."::class", $template);
        $template = str_replace('!method', "'$method'", $template);
        $template = str_replace('!routename', $routename ? "->name('$routename')" : '' , $template);
        $template = str_replace('!middleware', $middleware ? ($permission ? "->middleware(['$middleware','$permission'])" : "->middleware('$middleware')") : '' , $template);
    }else{
        $controller = $cli['controller'];
        if(!strpos($controller, 'Controller')){
            $controller .= 'Controller';
        }
        $namespace = $cli['namespace'];
        $template = file_get_contents(MickyCLI::BASE_MKY."/templates/route/crud.".MickyCLI::EXTENSION);
        $template = str_replace('!controller', "\App\\Http\\Controllers\\".$controller."::class", $template);
        $template = str_replace('!namespace', $namespace, $template);
    }
    $path = isset($cli['api']) ? 'api' : 'web';
    if (!file_exists("routes/$path.php")) {
        $template = "<"."?"."php\n\n"."use Core\Facades\Route;\n\n\n".$template;
        $model = fopen("routes/$path.php", "w");
        fwrite($model, $template);
    }else{
        $model = fopen("routes/$path.php", "a") or die("Unable to open file $routename");
        fwrite($model, "\n".$template);
    }
    print("$routename route created");
}
