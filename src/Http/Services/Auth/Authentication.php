<?php

namespace Http\Services\Auth;

use Http\Services\Request\RequestInterface;
use Models\User;

class Authentication implements AuthenticationInterface
{
    private $request;
    private $email;
    private $digest;
    private $nonce;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;

        if (!isset($request->httpAuthentication)) {
            throw new \Exception('HTTP_AUTHENTICATION is not set');
        }

        $httpAuthenticationArray = explode(':', $request->httpAuthentication);

        if (!isset($httpAuthenticationArray[0])) {
            throw new \Exception('HTTP_AUTHENTICATION email is not set');
        }

        $this->email = $httpAuthenticationArray[0];

        if (!isset($httpAuthenticationArray[1])) {
            throw new \Exception('HTTP_AUTHENTICATION nonce is not set');
        }

        $this->nonce = $httpAuthenticationArray[1];

        if (!isset($httpAuthenticationArray[2])) {
            throw new \Exception('HTTP_AUTHENTICATION digest is not set');
        }

        $this->digest = $httpAuthenticationArray[2];
    }

    public function perform()
    {
        $digestData = $this->request->requestMethod . '+' . $this->request->requestUri . '+' . $this->nonce;
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