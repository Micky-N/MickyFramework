<?php


namespace Core;


class Asset
{
    public static function goAsset(string $path)
    {
        $newPath = ROOT . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path;
        $newPath = str_replace('\\', '/', $newPath);
        return $newPath;
    }
}