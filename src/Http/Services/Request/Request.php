<?php

namespace Http\Services\Request;

use Helpers\Text;

class Request implements RequestInterface
{
    public function __construct()
    {
        $this->bootstrap();
    }

    private function bootstrap()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{Text::toLowerCamelCase($key)} = $value;
        }
    }
}