<?php

class Config
{
    private static $instance = null;
    private static $config = [];

    private function __construct(array $config)
    {
        self::$config = $config;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function get($key)
    {
        return self::$config[$key];
    }

    public static function getInstance(array $config)
    {
        return
            self::$instance === null
                ? self::$instance = new static($config)
                : self::$instance;
    }
}