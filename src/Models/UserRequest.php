<?php

namespace Models;

class UserRequest extends Model
{
    const TABLE_NAME = 'user_requests';

    public $id;
    public $user_id;
    public $date_start;
    public $date_end;
    public $status;

    public function allByUserId($userId)
    {
        $sql = 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':user_id' => $userId]);
        $items = $sth->fetchAll(\PDO::FETCH_CLASS, self::class);
        return $items;
    }

    public function save()
    {
        if (!empty($this->id)) {
            $sql = 'UPDATE ' . self::TABLE_NAME
                . ' SET date_start = :date_start, date_end = :date_end, status = :status WHERE id = :id';
            $sth = $this->dbh->prepare($sql);
            $sth->execute([
                ':id' => $this->id,
                ':date_start' => $this->date_start,
                ':date_end' => $this->date_end,
                ':status' => $this->status
            ]);
            return $this;
        } else {
            $sql = 'INSERT INTO ' . self::TABLE_NAME
                . ' (user_id, date_start, date_end) VALUES (:user_id, :date_start, :date_end)';
            $sth = $this->dbh->prepare($sql);
            $sth->execute([
                ':user_id' => $this->user_id,
                ':date_start' => $this->date_start,
                ':date_end' => $this->date_end
            ]);
            return $this->dbh->lastInsertId();
        }
    }
}