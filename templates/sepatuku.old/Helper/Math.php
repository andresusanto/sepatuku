<?php
class Helper_Math
{
    static function safeDivide($a, $b)
    {
        $c = 0.0;
        if ($b != 0) {
            $c = $a / $b;
        }
        return (double)$c;
    }

    static function getTh($number)
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13) $abbreviation = 'th';
        else $abbreviation = $ends[$number % 10];
        return $abbreviation;
    }
}
