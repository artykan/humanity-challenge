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
}