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
     */
    public static function query($statement, $class_name = null, $one = false);
}
