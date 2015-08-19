<?php
class Helper_Coupon
{
    static function calculateDiscountsXForY($controller, $items, $x, $y)
    {
        foreach ($items as $key => &$titem) {
            $titem['_ori_key'] = $key;
        }
        $sortedItems = $items;
        usort($sortedItems, function ($a, $b) { return $a['price'] - $b['price']; });
        $lin = array();
        foreach ($sortedItems as $key => $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                $sin = array(
                    'price' => $item['price'],
                    '_ori_key' => $item['_ori_key'],
                );
                $lin[] = $sin;
            }
        }
        $pstart = 0;
        $pend = $x;
        while ($pend <= count($lin)) {
            $tot = 0;
            for ($i = $pstart; $i < $pend; $i++) {
                $tot += $lin[$i]['price'];
            }
            $disAbs = 0;
            $disRate = 0;
            if ($tot > $y) {
                $disAbs = $y-$tot;
                $disRate = $disAbs/$tot;
                for ($i = $pstart; $i < $pend; $i++) {
                    $sinDis = $disRate * $lin[$i]['price'];
                    $lin[$i]['_dis_val'] = $sinDis;
                }
                $pstart = $pend;
                $pend += $x;
                continue;
            }
            $pstart++;
            $pend++;
        }
        $disVals = array();
        foreach ($lin as $linItem) {
            $key = $linItem['_ori_key'];
            if (isset($linItem['_dis_val'])) {
                if (!isset($disVals[$key])) {
                    $disVals[$key] = 0;
                }
                $disVals[$key] += $linItem['_dis_val'];
            }
        }
        return $disVals;
    }

    static function calculateDiscountsSecondItemAt($controller, $items, $at)
    {
        $totalQuantity = 0;
        foreach ($items as $key => &$titem) {
            $titem['_ori_key'] = $key;
            if ($titem['price'] > $at) {
                $totalQuantity += $titem['quantity'];
            }
        }
        $sortedItems = $items;
        usort($sortedItems, function ($a, $b) { return $a['price'] - $b['price']; });

        $discounts = array();
        $processed = 0;
        $limit = intval($totalQuantity / 2);
        foreach ($sortedItems as $key => $item) {
            if ($item['price'] > $at && $processed < $limit) {
                $needDiscount = min($limit - $processed, $item['quantity']);
                $processed += $needDiscount;
                $discounts[$item['_ori_key']] = -1 * $needDiscount * ($item['price'] - $at);
            }
        }

        return $discounts;

    }
}
