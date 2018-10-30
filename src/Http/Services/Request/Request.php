<?php

namespace Http\Services\Request;

use Helpers\TextHelper;
use Http\Services\Auth\Authentication;

class Request implements RequestInterface
{
    public $data;

    public function __construct(Authentication $auth)
    {
        $auth->perform();
        $this->bootstrap();
        $this->validate();
    }


    public function validate()
    {
        return true;
    }

    protected function bootstrap()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{TextHelper::toLowerCamelCase($key)} = $value;
        }

        $data = [];
        parse_str(file_get_contents('php://input'), $data);

        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }
}