<?php

namespace Http\Controllers;

use Http\Services\Auth\AuthenticationInterface;

class Controller
{
    public function __construct(AuthenticationInterface $auth)
    {
        $auth->perform();
    }
}