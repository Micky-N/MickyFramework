<?php

namespace Core;

class Cache
{

    private $cache;

    public function __construct()
    {
        $this->cache = config('cache');
    }

    public function addCache(string $directory, string $data)
    {
        $array = explode('/', $directory);
        $start = '';
        foreach ($array as $file) {
            $start .= $file;
            if (!file_exists($start)) {
                if (stripos($file, '.') != false) {
                    file_put_contents($start, $data);
                } else {
                    mkdir($start, 1);
                }
            }
            $start .= '/';
        }
    }

    public function removeCache(string $file)
    {
        function recursiveRemove($dir)
        {
            $structure = glob(rtrim($dir, "/") . '/*');
            if (is_array($structure)) {
                foreach ($structure as $file) {
                    if (is_dir($file)) recursiveRemove($file);
                    elseif (is_file($file)) unlink($file);
                }
            }

            if (stripos($dir, '/') != false) {
                if (is_dir($dir)) {
                    rmdir($dir);
                } else {
                    unlink($dir);
                }
            }
        }
        recursiveRemove($file);
    }

    private function getFile(string $file)
    {
        $file = str_replace(config('cache'), '', $file);
        return trim($file, '/');
    }
}
