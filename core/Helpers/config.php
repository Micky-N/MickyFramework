<?php

use Symfony\Component\Dotenv\Dotenv;


function configEnv()
{
    $dotenv = new Dotenv();
    $dotenv->load(dirname(__DIR__).'/../.env');
}

if (!function_exists('_env')) {
    function _env(string $key, string $default = null)
    {
        configEnv();
        return $_ENV[$key] ?: $default;
    }
}

if (!function_exists('config')) {
    function config($configName)
    {
        try {
            $config = include dirname(__DIR__).'/../bootstrap/Config.php';
            $configName = array_filter(explode('.', $configName));
            foreach ($configName as $c) {
                if (isset($config[$c])) {
                    $config = $config[$c];
                } else {
                    throw new Exception("Config '$c' does not exist", 12);
                }
            }
            return $config;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

if (!function_exists('includeAll')) {
    function includeAll($folder)
    {
        foreach (glob("{$folder}/*.php") as $filename) {
            include $filename;
        }
    }
}
