<?php

namespace Http\Services\Request;

use Helpers\TextHelper;

/**
 * Class Request
 */
class Request implements RequestInterface
{
    public $data;

    public function __construct()
    {
        $this->bootstrap();
        $this->validate();
    }


    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Populate Request object
     */
    protected function bootstrap()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{TextHelper::toLowerCamelCase($key)} = $value;
        }

        $data = [];

        // GET, POST, PUT/PATCH, DELETE data
        parse_str(file_get_contents('php://input'), $data);

        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }
}