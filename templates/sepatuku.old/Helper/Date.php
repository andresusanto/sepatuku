<?php
class Helper_Date
{
    static function formatSqlDateTime($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    static function formatSqlDate($time)
    {
        return date('Y-m-d', $time);
    }

    static function formatSgpDateTime($time, $withSeconds=TRUE)
    {
        $format = 'j F Y H:i';
        if ($withSeconds) {
            $format .= ':s';
        }
        return date($format, $time);
    }

    static function formatSgpDate($time)
    {
        $format = 'j F Y';
        return date($format, $time);
    }
}
