<?php

namespace App\Core\Session;

class Session implements \ArrayAccess
{
    private static $_session;

    private function __construct()
    {
        Session::start();
    }

    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
    }

    public static function close()
    {
        if (session_status() == PHP_SESSION_ACTIVE)
            session_write_close();
    }

    public static function unset()
    {
        Session::start();
        session_unset();
    }

    public static function getSession() : Session
    {
        return Session::$_session ?? new Session();
    }

    //region implement ArrayAccess
    public function offsetExists($offset)
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        return $_SESSION[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $_SESSION[] = $value;
        } else {
            $_SESSION[$offset] = $value;
        }
        Session::close();
    }

    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
        Session::close();
    }
    //endregion

}