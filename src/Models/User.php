<?php

namespace Models;

/**
 * Class User
 */
class User extends Model
{
    const TABLE_NAME = 'users';

    public $id;
    public $email;
    public $is_admin;

    /**
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    public function getByEmail($email)
    {
        $sql = 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':email' => $email]);
        $user = $sth->fetchObject(self::class);

        if (empty($user)) {
            throw new \Exception('No such user');
        }

        return $user;
    }
}