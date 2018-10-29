<?php

namespace Http\Services\Request;

use Helpers\Text;

class Request implements RequestInterface
{
    private static $instance = null;

    private function __construct()
    {
        $this->bootstrap();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function bootstrap()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{Text::toLowerCamelCase($key)} = $value;
        }

        $data = [];
        parse_str(file_get_contents('php://input'), $data);

        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public static function getInstance()
    {
        return
            self::$instance === null
                ? self::$instance = new static()
                : self::$instance;
    }
}