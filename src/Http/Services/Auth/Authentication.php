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

        if (!isset($this->request->httpAuthentication)) {
            throw new \Exception('Authentication header is not set');
        }

        $httpAuthenticationArray = explode(':', $this->request->httpAuthentication);

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

    public function reconstructDigest($secret)
    {
        $digestData = $this->request->requestMethod . '+' . $this->request->requestUri . '+' . $this->nonce;
        $digest = substr(base64_encode(hash_hmac('sha256', $digestData, $secret)), 0, 10);

        return $digest;
    }

    public function perform()
    {
        $digest = $this->reconstructDigest(\Config::get('AUTH_HMAC_SECRET'));

        if ($digest != $this->digest) {
            throw new \Exception('Authentication failed');
        }

        $user = new User;
        $user = $user->getByEmail($this->email);
        CurrentUser::getInstance($user);

        return true;
    }
}