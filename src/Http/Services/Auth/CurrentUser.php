<?php

namespace Http\Services\Auth;

use Models\User;

/**
 * Class CurrentUser
 */
class CurrentUser
{
    public static $id;
    public static $email;
    public static $is_admin;

    private static $instance = null;

    /**
     * CurrentUser constructor.
     * @param User|null $user
     */
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

    /**
     * @param User|null $user
     * @return CurrentUser|null
     */
    public static function getInstance(User $user = null)
    {
        return
            self::$instance === null
                ? self::$instance = new static($user)
                : self::$instance;
    }
}