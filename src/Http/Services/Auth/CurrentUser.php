<?php

namespace Http\Services\Auth;

use Models\User;

class CurrentUser implements CurrentUserInterface
{
    public static $id;
    public static $email;
    public static $is_admin;

    private static $instance = null;

    private function __construct(User $user = null)
    {
        if (!is_null($user)) {
            self::$id = $user->id;
            self::$email = $user->email;
            self::$is_admin = $user->is_admin;
        }
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance(User $user = null)
    {
        return
            self::$instance === null
                ? self::$instance = new static($user)
                : self::$instance;
    }
}