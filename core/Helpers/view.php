<?php

use Core\Asset;

if (!function_exists('asset')) {
    function asset($path)
    {
        $newPath = ROOT . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path;
        $newPath = str_replace('\\', '/', $newPath);
        return $newPath;
    }
}
