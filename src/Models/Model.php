<?php

namespace Models;

class Model
{
    const TABLE_NAME = 'undefined';

    protected $dbh;

    public function __construct()
    {
        $dsn = 'mysql:host=mariadb;dbname=' . \Config::get('MYSQL_DATABASE');
        $this->dbh = new \PDO($dsn, \Config::get('MYSQL_USER'), \Config::get('MYSQL_PASSWORD'));
    }

    public function getById(int $id)
    {
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            ':id' => $id
        ]);
        $model = $sth->fetchObject(static::class);

        if (empty($model)) {
            $message = 'No such ' . static::class;
            throw new \Exception($message);
        }

        return $model;
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::TABLE_NAME . ' WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            ':id' => $this->id
        ]);
        return true;
    }
}