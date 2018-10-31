<?php

namespace Helpers;

class DateHelper
{
    public static function isCorrectDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function isEndGreatedThanStart($start, $end)
    {
        $start = new \DateTime(trim($start));
        $end = new \DateTime(trim($end));
        $interval = $end->diff($start);
        return $interval->invert ? true : false;
    }

    public static function getWorkingDaysCount($start, $end, array $holidays = [])
    {
        $end = strtotime($end);
        $start = strtotime($start);

        $days = ($end - $start) / 86400 + 1;

        $noFullWeeks = floor($days / 7);
        $noRemainingDays = fmod($days, 7);

        $theFirstDayOfWeek = date("N", $start);
        $theLastDayOfWeek = date("N", $end);

        if ($theFirstDayOfWeek <= $theLastDayOfWeek) {
            if ($theFirstDayOfWeek <= 6 && 6 <= $theLastDayOfWeek) $noRemainingDays--;
            if ($theFirstDayOfWeek <= 7 && 7 <= $theLastDayOfWeek) $noRemainingDays--;
        } else {
            if ($theFirstDayOfWeek == 7) {
                $noRemainingDays--;
                if ($theLastDayOfWeek == 6) {
                    $noRemainingDays--;
                }
            } else {
                $noRemainingDays -= 2;
            }
        }

        $workingDays = $noFullWeeks * 5;
        if ($noRemainingDays > 0) {
            $workingDays += $noRemainingDays;
        }

        foreach ($holidays as $holiday) {
            $timestamp = strtotime($holiday);
            if ($start <= $timestamp && $timestamp <= $end && date("N", $timestamp) != 6 && date("N", $timestamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }
}