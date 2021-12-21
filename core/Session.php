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
     *
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function setFlashMessageOnType(string $type, string $name, $message): void
    {
        if (isset($_SESSION[self::FLASH][$type][$name])) {
            unset($_SESSION[self::FLASH][$type][$name]);
        }
        $_SESSION[self::FLASH][$type][$name] = $message;
    }

    /**
     * @param string $type
     * 
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

    public function getAll()
    {
        return $_SESSION;
    }

    public function getConstant($const)
    {
        if(defined("self::$const")){
            return constant("self::$const");
        }
    }
}
