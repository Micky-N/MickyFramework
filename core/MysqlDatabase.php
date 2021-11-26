<?php

namespace Core;

use PDO;
use Core\Database;
use Core\Interfaces\DatabaseInterface;

class MysqlDatabase extends Database implements DatabaseInterface
{

    public static function getConnection()
    {
        if(is_null(self::$connection) || !method_exists(self::$connection, 'getAttribute') || self::$connection->getAttribute(PDO::ATTR_DRIVER_NAME) !== 'mysql'){
            $config = config('connection.mysql');
            $dsn = 'mysql:dbname=' . $config['name'] . ';host=' . $config['host'];
            $pdo = new PDO($dsn, $config['user'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection = $pdo;
        }
        return self::$connection;
    }

    public static function query($statement, $class_name = null, $one = false)
    {
        $req = self::getConnection()->query($statement);
        $class_name === null ? $req->setFetchMode(PDO::FETCH_OBJ) : $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        return $one ? $req->fetch() : $req->fetchAll();
    }

    public static function prepare($statement, $attribute, $class_name = null, $one = false)
    {
        $req = self::getConnection()->prepare($statement);
        $res = $req->execute($attribute);
        if(
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ){
            return $res;
        }
        $class_name === null ? $req->setFetchMode(PDO::FETCH_OBJ) : $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        return $one ? $req->fetch() : $req->fetchAll();
    }

    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }

}