<?php

namespace Core;

use Exception;

class App
{
    protected static $registries = [];

    public static function bind($key, $value)
    {
        self::$registries[$key] = $value;
    }

    public static function get($key)
    {
        if (!array_key_exists($key, self::$registries)) {
            throw new Exception("No {$key} is bound in the container");
        }

        return self::$registries[$key];
    }
}