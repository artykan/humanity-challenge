<?php

namespace Models;

use Helpers\DateHelper;

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

    public function approvedByUserIdAndYear($userId, $year)
    {
        $sql = 'SELECT * FROM ' . self::TABLE_NAME
            . ' WHERE user_id = :user_id AND ( YEAR(date_start) = :year OR YEAR(date_end) = :year ) AND status = :status';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            ':user_id' => $userId,
            ':year' => $year,
            ':status' => 'approved'
        ]);
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

    public function remainder($userId, $year)
    {
        $approvedDaysCount = 0;
        $items = $this->approvedByUserIdAndYear($userId, $year);
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemDateStart = \DateTime::createFromFormat('Y-m-d', $item->date_start);
                $itemDateStartYear = $itemDateStart->format('Y');
                $dateStart = ($itemDateStartYear != $year) ? $year . '-01-01' : $item->date_start;

                $itemDateEnd = \DateTime::createFromFormat('Y-m-d', $item->date_end);
                $itemDateEndYear = $itemDateEnd->format('Y');
                $dateEnd = ($itemDateEndYear != $year) ? $year . '-12-31' : $item->date_end;

                $approvedDaysCount += DateHelper::getWorkingDaysCount(
                    $dateStart,
                    $dateEnd,
                    \Config::get('HOLIDAYS')
                );
            }
        }

        $remainderDaysCount = \Config::get('VACATION_DAYS_COUNT') - $approvedDaysCount;

        return $remainderDaysCount;
    }
}