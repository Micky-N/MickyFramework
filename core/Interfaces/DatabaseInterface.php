<?php

namespace Core\Interfaces;

interface DatabaseInterface
{

    /**
     * Renvoi une connection 
     * Créer si null
     */
    public static function getConnection();

    /**
     * Renvoi une requête
     * @param $statement
     * @param null $class_name
     * @param bool $one
     */
    public static function query($statement, $class_name = null, $one = false);
}
