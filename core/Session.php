<?php


namespace Core;


class Session
{
    /**
     * Assure que la session est démarré
     *
     */
    public function __construct()
    {
        if(session_status() === PHP_SESSION_NONE){
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

    public function getAll()
    {
        return $_SESSION;
    }
}