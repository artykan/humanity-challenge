<?php

/**
 * Class Config
 */
class Config
{
    private static $instance = null;
    private static $config = [];

    /**
     * Config constructor.
     * @param array $config
     */
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

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return self::$config[$key];
    }

    /**
     * @param array $config
     * @return Config|null
     */
    public static function getInstance(array $config)
    {
        return
            self::$instance === null
                ? self::$instance = new static($config)
                : self::$instance;
    }
}