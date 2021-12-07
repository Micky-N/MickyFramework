<?php


if(!function_exists('exportToArray')){
    function exportToArray($expression)
    {
        $export = var_export($expression, TRUE);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        return preg_replace(array_keys($patterns), array_values($patterns), $export);
    }
}

if(!function_exists('_readLine')){
    function _readLine(string $file)
    {
        $ptr = fopen($file, "r");
        $content = fread($ptr, filesize($file));
        fclose($ptr);
        return explode("\n", $content);
    }
}