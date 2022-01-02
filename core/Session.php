<?php


namespace Core;


class Session
{

    const FLASH = 'FLASH_MESSAGES';
    const FLASH_ERROR = 'error';
    const FLASH_SUCCESS = 'success';
    const FLASH_MESSAGE = 'message';

    /**
     * Assure que la session est démarré
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retourne la valeur d'une clé de session
     * sinon retourne la valeur par defaut
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
    }

    /**
     * Inscrit une clé valeur dans la session
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Supprime une clé de la session
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Inscrit un type de flash et un message
     * dans la session
     *
     * @param string $type
     * @param string $name
     * @param $message
     */
    public function setFlashMessageOnType(string $type, string $name, $message): void
    {
        if (isset($_SESSION[self::FLASH][$type][$name])) {
            unset($_SESSION[self::FLASH][$type][$name]);
        }
        $_SESSION[self::FLASH][$type][$name] = $message;
    }

    /**
     * Retourne les messages d'un type de flash
     *
     * @param string $type
     * @return array|void
     */
    public function getFlashMessagesByType(string $type)
    {
        if (!isset($_SESSION[self::FLASH][$type])) {
            return;
        }
        $flashType = $_SESSION[self::FLASH][$type];
        unset($_SESSION[self::FLASH][$type]);
        return $flashType;
    }

    /**
     * Retourne toute la session
     *
     * @return array
     */
    public function getAll()
    {
        return $_SESSION;
    }

    /**
     * Retourne la valeur d'une constante
     *
     * @param $const
     * @return mixed
     */
    public function getConstant($const)
    {
        if(defined("self::$const")){
            return constant("self::$const");
        }
    }
}
