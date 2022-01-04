<?php

namespace Core;


use Exception;

class Cache
{

    private $cache;

    public function __construct()
    {
        $this->cache = config('cache');
    }

    /**
     * Créer et ajouter un ficher/dossier
     * dans le cache
     *
     * @param string $directory
     * @param string $data
     */
    public function addCache(string $directory, string $data)
    {
        $array = explode('/', $directory);
        $start = '';
        foreach ($array as $file) {
            $start .= $file;
            if(strpos($file, '.') !== false){
                file_put_contents($start, $data);
            } else {
                if(!file_exists($start)){
                    mkdir($start, 1);
                }
            }
            $start .= '/';
        }
    }

    /**
     * Supprime un fichier/dossier dans le cache
     * @param string $file
     */
    public function removeCache(string $file)
    {
        $structure = glob(rtrim($file, "/") . '/*');
        if(is_array($structure)){
            foreach ($structure as $file) {
                if(is_dir($file)) $this->removeCache($file);
                elseif(is_file($file)) unlink($file);
            }
        }

        if(stripos($file, '/') != false){
            if(is_dir($file)){
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }

    /**
     * Retourne le nom du fichier/dossier
     *
     * @param string $file
     * @return string
     * @throws Exception
     */
    private function getFile(string $file)
    {
        $file = str_replace(config('cache'), '', $file);
        return trim($file, '/');
    }
}
