<?php


namespace Core;


class Session
{

    const FLASH = 'FLASH_MESSAGES';

    const FLASH_ERROR = 'error';
    const FLASH_WARNING = 'warning';
    const FLASH_INFO = 'info';
    const FLASH_SUCCESS = 'success';

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

    public function setFlashMessage(string $type, string $name, string $message): void
    {
        // remove existing message with the name
        if (isset($_SESSION[self::FLASH][$type][$name])) {
            unset($_SESSION[self::FLASH][$type][$name]);
        }
        // add the message to the session
        $_SESSION[self::FLASH][$type][$name] = ['message' => $message];
    }

    public function getAll()
    {
        return $_SESSION;
    }
}
