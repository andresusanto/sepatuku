<?php
class Helper_Jne
{
    static function getPackingKayu($items)
    {
        $weight = Helper_Cart::getTotalWeightOfItems($items);
        if ($weight <  3000) return 8500;
        if ($weight <  5000) return 11500;
        if ($weight < 11000) return 32500;
        if ($weight < 16000) return 42000;
        if ($weight < 21000) return 51500;
        if ($weight < 26000) return 59000;
        return 100000;
    }

    static function getJneInsurance($items)
    {
        return 5000 + self::roundUp(Helper_Cart::getTotalPriceOfItems($items)*0.002, 3);
    }

    static function roundUp($x, $p)
    {
        return ceil($x/pow(10, $p)) * pow(10, $p);
    }
}
