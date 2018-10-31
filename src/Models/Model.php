<?php

namespace Models;

/**
 * Class Model
 */
class Model
{
    const TABLE_NAME = 'undefined';

    protected $dbh;

    public function __construct()
    {
        $dsn = 'mysql:host=mariadb;dbname=' . \Config::get('MYSQL_DATABASE');
        $this->dbh = new \PDO($dsn, \Config::get('MYSQL_USER'), \Config::get('MYSQL_PASSWORD'));
    }

    /**
     * @return array
     */
    public function all()
    {
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE 1 = 1';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $items = $sth->fetchAll(\PDO::FETCH_CLASS, static::class);
        return $items;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * @return bool
     */
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