<?php

namespace App;

abstract class App
{
    public static function start()
    {
        spl_autoload_register(function ($class_name) {
            include $class_name . '.php';
        });
    }
}