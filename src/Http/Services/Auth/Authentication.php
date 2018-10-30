<?php

namespace Http\Services\Auth;

use Models\User;

class Authentication implements AuthenticationInterface
{
    private $email;
    private $digest;
    private $nonce;

    public function __construct()
    {
        if (!isset($_SERVER['HTTP_AUTHENTICATION'])) {
            throw new \Exception('Authentication header is not set');
        }

        $httpAuthenticationArray = explode(':', $_SERVER['HTTP_AUTHENTICATION']);

        if (!isset($httpAuthenticationArray[0])) {
            throw new \Exception('Authentication header email is not set');
        }

        $this->email = $httpAuthenticationArray[0];

        if (!isset($httpAuthenticationArray[1])) {
            throw new \Exception('Authentication header nonce is not set');
        }

        $this->nonce = $httpAuthenticationArray[1];

        if (!isset($httpAuthenticationArray[2])) {
            throw new \Exception('Authentication header digest is not set');
        }

        $this->digest = $httpAuthenticationArray[2];
    }

    public function perform()
    {
        $digestData = $_SERVER['REQUEST_METHOD'] . '+' . $_SERVER['REQUEST_URI'] . '+' . $this->nonce;
        $digest = substr(base64_encode(hash_hmac('sha256', $digestData, \Config::get('AUTH_HMAC_SECRET'))), 0, 10);

        if ($digest != $this->digest) {
            throw new \Exception('Authentication failed');
        }

        $user = new User;
        $user = $user->getByEmail($this->email);
        CurrentUser::getInstance($user);

        return true;
    }
}