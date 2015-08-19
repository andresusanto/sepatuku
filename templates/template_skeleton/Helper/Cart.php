<?php

class Helper_Cart
{
    public static $TIKI_API_BASE_URL = "http://203.77.231.130/services/api.cfc";

    static function cartToDiscountsString($cart)
    {
        $str = implode("\n", array_map(function ($x) use ($cart) {
            $title = $x['code'];
            $val = Helper_String::dollarFormat($x['value'], 2, $cart['currency_symbol']);
            return "$title ($val)";
        }, $cart['discounts']));
        if ($str) {
            $str = "Discount(s):\n" . $str;
        }
        return $str;
    }

    static function _isCouponValidGeneral($coupon, $member, $items, $cartObj, $controller=NULL)
    {
        if (!is_object($coupon)) {
            return FALSE;
        }
        $shippingMethods = Helper_Structure::getObjCall($coupon, 'getCouponShippingMethods');
        $shippingDest = Helper_Structure::getObjCall($cartObj, 'getShippingDestination');
        $couponCode = $coupon->getCouponCode();
        if ($controller) {
            if ($coupon->getStartDate()) {
                $time = strtotime($coupon->getStartDate());
                if ($controller->now < $time) {
                    $coupon->_invalidMessage = "Coupon $couponCode cannot be used yet.";
                    return FALSE;
                }
            }
            if ($coupon->getExpiryDate()) {
                $time = strtotime($coupon->getExpiryDate());
                if ($controller->now > $time) {
                    $coupon->_invalidMessage = "Coupon $couponCode has expired.";
                    return FALSE;
                }
            }
        }
        if ($shippingMethods && !in_array($shippingDest, $shippingMethods)) {
            $coupon->_invalidMessage = "Coupon $couponCode does not apply for your selected shipping method.";
            return FALSE;
        }
        if ($coupon->getIsMemberOnly()) {
            if ($member) {
                if (($memberType = $coupon->getMemberType()) && ($memberType != $member->getMemberLevel())) {
                    $coupon->_invalidMessage = "Coupon $couponCode is only available for member $memberType.";
                    return FALSE;
                }
            } else {
                $coupon->_invalidMessage = "Coupon $couponCode is only available for member.";
                return FALSE;
            }
        }
        $price = array_sum(array_map(function($item) {return $item['quantity']*$item['price'];}, $items));
        if ($price < $coupon->getMinPurchase()) {
            $coupon->_invalidMessage = "Coupon $couponCode has a minimum purchase value.";
            return FALSE;
        }
        $nUsage = $coupon->getNumOfUsage();
        if ($nUsage === 0 || $nUsage === '0') {
            $coupon->_invalidMessage = "Coupon $couponCode has been used up.";
            return FALSE;
        }
        return TRUE;
    }

    static function _isCouponValidForProduct($controller, $coupon, $product)
    {
        if (!is_object($coupon)) {
            return FALSE;
        }
        if ($coupon->getItemType() == 'cart') {
            return TRUE;
        }
        $agg = self::getAggregator($product, $controller);
        if (Helper_Structure::_getAttrFromObj('SalePrice', $product, $agg)) {
            return FALSE;
        }
        if ($coupon->getIsAllProducts()) {
            return TRUE;
        }
        if ($ids = $coupon->getProductIds()) {
            if (in_array($product->getId(), $ids)) {
                return TRUE;
            }
        }
        if (self::_isProductInCouponCategories($controller, $product, $coupon)) {
            return TRUE;
        }
        return FALSE;
    }

    static function _isProductInCouponCategories($controller, $product, $coupon)
    {
        if ($labels = $coupon->getCategories()) {
            $agg = self::getAggregator($product, $controller);
            $attr = 'Label';
            if ($controller->_getCategoryType('products') == 'object') {
                $attr = 'GeneralCategoryId';
            }
            if (in_array(Helper_Structure::_getAttrFromObj($attr, $product, $agg), $labels)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    static function _getDiscountArr($coupon)
    {
        $type = '';
        $disValue = 0;
        if (is_object($coupon)) {
            if ($coupon->getDiscountPercentage()) {
                $type = 'percentage';
                $disValue = $coupon->getDiscountPercentage();
            } else if ($coupon->getDiscountAmount()) {
                $type = 'absolute';
                $disValue = $coupon->getDiscountAmount();
            }
        }
        return array(
            'type' => $type,
            'value' => $disValue,
        );
    }

    static function _applyCouponToProductDiscountArr($controller, $coupon, $product)
    {
        $arr = array();
        $arr['type'] = 'absolute';
        $arr['value'] = 0;
        if (self::_isCouponValidForProduct($controller, $coupon, $product)) {
            $disArr = self::_getDiscountArr($coupon);
            $arr = $disArr;
        }
        return $arr;
    }

    static function _applySingleDiscount($discounted, $discount)
    {
        $discValSingle = 0;
        if (isset($discount['value'])) {
            if ($discount['type'] == 'percentage') {
                $discValSingle = -$discount['value']/100 * ($discounted);
            } else if ($discount['type'] == 'absolute') {
                $discValSingle = -$discount['value'];
            } else if ($discount['type'] == 'force') {
                $discValSingle = $discount['value'] - $discounted;
            }
        }
        return $discValSingle;
    }

    static function _calculateMultipleDiscounts($price, $discounts)
    {
        $discounted = $price;
        foreach ($discounts as $discount) {
            $discValSingle = self::_applySingleDiscount($discounted, $discount);
            $discounted += $discValSingle;
        }
        $discVal = $discounted - $price;
        return $discVal;
    }

    static function _preProcessCoupon($coupon, $tempItems)
    {
        $pre = clone $coupon;
        if (($pre->getItemType() == 'cart') && !$pre->getDiscountPercentage()) {
            $totalTempPrice = array_sum(array_map(function ($x) { return $x['temp_price']; }, $tempItems));
            $percentage = 0;
            if ($totalTempPrice) {
                $percentage = ($pre->getDiscountAmount()/$totalTempPrice) * 100.0;
            }
            $pre->setDiscountPercentage($percentage);
        }
        return $pre;
    }

    static function _isCouponValidGeneralOfController($controller, $coupon)
    {
        $member = $controller->getMemberObj();
        $cartObj = $controller->getCartObj();
        $items = self::_getCartObjItems($controller, $cartObj);
        return self::_isCouponValidGeneral($coupon, $member, $items, $cartObj, $controller);
    }

    static function _calculateDiscountsOfItems($controller, $tempItems, $coupon)
    {
        $customRules = $controller->getKrcoConfigValue('cart', 'predefined_coupon_rules');
        if (isset($customRules[$coupon->getPredefinedRule()])) {
            $rule = $customRules[$coupon->getPredefinedRule()];
            $disVals = $rule($controller, $tempItems);
            return $disVals;
        }
        $disVals = array();
        foreach ($tempItems as $key => $item) {
            $disArr = self::_applyCouponToProductDiscountArr($controller, $coupon, $item['product_obj']);
            $disVals[$key] = self::_applySingleDiscount($item['temp_price'], $disArr);
        }
        return $disVals;
    }

    static function _iterateItemsWithCoupon($controller, &$tempItems, $coupon)
    {
        $disVal = 0;
        $disVals = self::_calculateDiscountsOfItems($controller, $tempItems, $coupon);
        foreach ($tempItems as $key => &$item) {
            $disValSingle = Helper_Structure::getArrayValue($disVals, $key);
            $newPrice = $item['temp_price'] + $disValSingle;
            $disVal += $newPrice - $item['temp_price'];
            $item['temp_price'] = $newPrice;
        }
        return $disVal;
    }

    static function _getDiscountsOfCoupons($controller, $items, $coupons, $member, $cartObj)
    {
        $itemDiscounts = array();
        $shippingDiscount = 0;
        if (isset($controller->products)) {
            $tempItems = $items;
            $dbName = $controller->getKrcoConfigValue('products', 'db_name');
            $getByFid = 'get' . $dbName . 'ByFriendlyId';
            foreach ($tempItems as &$item) {
                $product = $controller->getSingleObject('products', $getByFid, array($item['fid']));
                $item['temp_price'] = $item['price'] * $item['quantity'];
                $item['product_obj'] = $product;
            }
            $shippingDiscounts = array();
            foreach ($coupons as $tempCoupon) {
                if (is_object($tempCoupon)) {
                    $coupon = self::_preProcessCoupon($tempCoupon, $tempItems);
                    if (!self::_isCouponValidGeneral($coupon, $member, $items, $cartObj, $controller)) {
                        continue;
                    }
                    $disVal = self::_iterateItemsWithCoupon($controller, $tempItems, $coupon);
                    $couponType = 'general';
                    if (isset($coupon->couponType)) {
                        $couponType = $coupon->couponType;
                    }
                    $discount = array(
                        'title' => $coupon->getTitle(),
                        'code' => $coupon->getCouponCode(),
                        'value' => $disVal,
                        'type' => $couponType,
                    );
                    $itemDiscounts[] = $discount;
                    $shipDisc = self::_applyShippingDiscount($coupon, $member);
                    $shippingDiscounts[] = $shipDisc;
                }
            }
        }
        $discounts = array(
            'item' => $itemDiscounts,
            //'shipping' => $shippingDiscount,
            'shipping_discounts' => $shippingDiscounts,
        );
        return $discounts;
    }

    static function _applyShippingDiscount($coupon, $member)
    {
        $shipDisc = array(
            'type' => 'percentage',
            'value' => $coupon->getShippingDiscountPercentage(),
        );
        return $shipDisc;
    }

    static function _getAutoCoupons($controller, $member)
    {
        $coupons = $controller->getObjects('coupons', 'getAutoCoupons', array());
        if (!$coupons) {
            $coupon = self::_getMemberCoupon($controller, $member);
            if ($coupon) {
                $coupons = array($coupon);
            }
        }
        if (!is_array($coupons)) {
            $coupons = array();
        }
        foreach ($coupons as $coupon) {
            if (is_object($coupon)) {
                $coupon->couponType = 'special';
            }
        }
        return $coupons;
    }

    static function _getMemberCoupon($controller, $member)
    {
        $coupon = NULL;
        $isEligible = TRUE;
        $isEligibleFunction = $controller->getKrcoConfigValue('cart', 'member_coupon_is_eligible');
        if ($isEligibleFunction) {
            $isEligible = $isEligibleFunction($controller, $member);
        }
        if ($isEligible && $member && !is_null($controller->getKrcoConfigValue('cart', 'member_coupon'))) {
            $coupon = $controller->getKrcoConfigValue('cart', 'member_coupon');
        }
        return $coupon;
    }

    static function _getPointDiscountAmount($controller, $nPointsUsed, $member)
    {
        $amount = 0;
        $pointRule = $controller->getPointRule();
        if ($pointRule && is_array($pointRule)) {
            $dollarPerPoint = Helper_Structure::getArrayValue($pointRule, 'dollar_per_point');
            $amount = $nPointsUsed * $dollarPerPoint;
        }
        return $amount;
    }

    static function _getNPointsUsed($cartObj, $member)
    {
        $memberPoint = 0;
        if (is_object($member)) {
            $memberPoint = $member->getPoint();
        }
        $nPointsUsed = min($memberPoint, $cartObj->getPoint());
        return $nPointsUsed;
    }

    static function _generatePointCoupon($controller, $cartObj, $member)
    {
        $coupon = NULL;
        $nPointsUsed = self::_getNPointsUsed($cartObj, $member);
        $discAmount = self::_getPointDiscountAmount($controller, $nPointsUsed, $member);
        if ($discAmount) {
            $coupon = new Coupon();
            $couponCode = "POINT$nPointsUsed";
            if (isset($controller->krco_config['cart']['point_coupon']['code'])) {
                $couponCode = str_replace('{point}', $nPointsUsed, $controller->krco_config['cart']['point_coupon']['code']);
            }
            $coupon->setCouponCode($couponCode);
            $couponTitle = "$nPointsUsed Points";
            if (isset($controller->krco_config['cart']['point_coupon']['title'])) {
                $couponTitle = str_replace('{point}', $nPointsUsed, $controller->krco_config['cart']['point_coupon']['title']);
            }
            $coupon->setTitle($couponTitle);
            $coupon->setDiscountAmount($discAmount);
            $coupon->setIsAllProducts(TRUE);
            $coupon->setItemType('cart');
            $coupon->couponType = 'special';
        }
        return $coupon;
    }

    static function _generateCoupons($controller, $cartObj, $couponCode, $member)
    {
        $coupon = NULL;
        if (isset($controller->coupons)) {
            $coupon = $controller->getObjBySomething($couponCode, 'coupons', 'Coupon', 'CouponCode');
        }
        $autoCoupons = self::_getAutoCoupons($controller, $member);
        $coupons = array_merge($autoCoupons, array($coupon));
        $pointCoupon = self::_generatePointCoupon($controller, $cartObj, $member);
        if ($pointCoupon) {
            $coupons[] = $pointCoupon;
        }
        $codes = array();
        $finalCoupons = array();
        /* remove duplicates */
        foreach ($coupons as $cou) {
            $code = NULL;
            if (method_exists($cou, 'getCouponCode')) {
                $code = $cou->getCouponCode();
            }
            if (!in_array($code, $codes)) {
                $finalCoupons[] = $cou;
                $codes[] = $code;
            }
        }
        return $finalCoupons;
    }

    static function _getAllShippingRules($controller, $obj)
    {
        $normalRule = self::getShippingRule($controller, $obj);
        $backorderRule = NULL;
        $backShipDest = $obj->getBackorderShippingDestination();
        //if (isset($backShipDest) && ($backShipDest != $obj->getShippingDestination())) {
        if (isset($backShipDest)) {
            $backorderRule = self::getBackorderShippingRule($controller, $obj);
        }
        $arr = array(
            'normal' => $normalRule,
            'backorder' => $backorderRule,
        );
        return $arr;
    }

    static function _getCartObjItems($controller, $obj)
    {
        $items = array();
        foreach ($obj->getCartItems() as $cartItem) {
            $itemArr = self::cartItemToArr($cartItem, $controller);
            if (isset($itemArr)) {
                $items[] = $itemArr;
            }
        }
        return $items;
    }

    static function _getShippingVals($controller, $obj, $items, $couponCode, $member, $discounts)
    {
        $shippingRules = self::_getAllShippingRules($controller, $obj);
        $shippingRule = $shippingRules['normal'];
        $backorderShippingRule = $shippingRules['backorder'];
        $cartInfo = array(
            'items' => array_filter($items, function ($x) { return (!isset($x['is_free_shipping']) || !$x['is_free_shipping']); }),
        );
        if ($controller->getKrcoConfigValue('cart', 'consider_discount_when_calculate_shipping')) {
            $cartInfo['total_discount'] = self::getTotalDiscountOfDiscArrs($discounts['item']);
        }
        $shippingVals = self::applyShipping($controller, $obj, $cartInfo, $shippingRule, $backorderShippingRule);
        return $shippingVals;
    }

    static function _getCheckoutStatus($controller, $obj, $items, $shippingVals)
    {
        if ($checkoutFilter = $controller->getKrcoConfigValue('cart', 'checkout_filter')) {
            $isCheckoutEnabled = $checkoutFilter($obj, $items);
            if (is_string($isCheckoutEnabled)) {
                return array(
                    'is_enabled' => FALSE,
                    'disabled_reason' => $isCheckoutEnabled,
                );
            }
            if (!$isCheckoutEnabled) {
                return array(
                    'is_enabled' => FALSE,
                    'disabled_reason' => 'Invalid cart.',
                );
            }
        }
        if (empty($shippingVals['is_valid'])) {
            return array(
                'is_enabled' => FALSE,
                'disabled_reason' => 'Invalid shipping method or destination.',
            );
        }
        return array(
            'is_enabled' => TRUE,
            'disabled_reason' => '',
        );
    }

    static function cartToArr($obj, $controller, $couponCode=NULL, $member=NULL)
    {
        if (!$obj) {
            return NULL;
        }
        $coupon = NULL;
        if (!$couponCode) {
            $couponCode = $obj->getCouponCode();
        }
        if (!$member) {
            $member = $controller->getMemberObj();
        }
        $items = self::_getCartObjItems($controller, $obj);
        $coupons = self::_generateCoupons($controller, $obj, $couponCode, $member);
        $cart_segment = $controller->getKrcoConfigValue('cart', 'segment');
        $shippingDest = $obj->getShippingDestination();
        $discounts = self::_getDiscountsOfCoupons($controller, $items, $coupons, $member, $obj);
        $shippingVals = self::_getShippingVals($controller, $obj, $items, $couponCode, $member, $discounts);
        $shippingVal = $shippingVals['summary'];
        $shippingDiscount = self::_calculateMultipleDiscounts($shippingVal, $discounts['shipping_discounts']);
        $total = array_sum(array_map(function ($item) {return $item['quantity'];}, $items));
        $checkoutStatus = self::_getCheckoutStatus($controller, $obj, $items, $shippingVals);
        $isDiscountIgnoreShipping = FALSE;
        if ($controller->getKrcoConfigValue('cart', 'is_discount_ignore_shipping')) {
            $isDiscountIgnoreShipping = TRUE;
        }
        $pointRule = $controller->getPointRule();
        $pointRule = array(
            'point_per_dollar' => Helper_Structure::getArrayValue($pointRule, 'point_per_dollar'),
            'dollar_per_point' => Helper_Structure::getArrayValue($pointRule, 'dollar_per_point'),
        );
        $arr = array(
            'code' => $obj->getCode(),
            'currency_symbol' => $controller->getCurrencySymbol(),
            'items' => $items,
            'shipping' => $shippingVal,
            'shipping_details' => $shippingVals['details'],
            'shipping_method' => $shippingDest,
            'backorder_shipping_method' => $obj->getBackorderShippingDestination(),
            'shipping_destination' => $shippingDest,
            'backorder_shipping_destination' => $obj->getBackorderShippingDestination(),
            'shipping_country' => $obj->getShippingCountry(),
            'shipping_city' => $obj->getShippingCity(),
            'npoints' => self::_getNPointsUsed($obj, $member),
            'discounts' => $discounts['item'],
            'shipping_discount' => $shippingDiscount,
            'coupon_code' => $couponCode,
            'link' => $controller->composeLink("/$cart_segment"),
            'checkout_link' => $controller->composeLink("/$cart_segment/place_order", array( 'cart_code' => $obj->getCode(),)),
            'total_items' => $total,
            'total_weight' => self::getTotalWeightOfItems($items),
            'is_checkout_enabled' => $checkoutStatus['is_enabled'],
            'checkout_disabled_reason' => $checkoutStatus['disabled_reason'],
            'is_discount_ignore_shipping' => $isDiscountIgnoreShipping,
            'delivery_timestamp' => strtotime($obj->getDeliveryDate()),
            'tax_rate' => self::getTaxRateOfCart($controller, $obj),
            'point_rule' => $pointRule,
        );
        $taxableTotal = Helper_Cart::getTaxableTotal($arr);
        $taxVal = $arr['tax_rate'] * $taxableTotal;
        $arr['tax'] = $taxVal;
        $arr['grand_total'] = $taxableTotal + $taxVal;
        $arr['item_total'] = Helper_Cart::getItemTotal($items);
        $arr['npoints_earned'] = Helper_Cart::getPointsEarned($controller, $arr);
        return $arr;
    }

    public static $vatRates = array(
        'sg' => 0.07,
        'id' => 0.10,
    );

    static function getTaxRateOfCart($controller, $obj)
    {
        $getTaxRate = $controller->getKrcoConfigValue('cart', 'get_tax_rate');
        $taxRate = 0;
        if ($vatCountry = $controller->getDepConfigValue('vat_country')) {
            if (strtolower($vatCountry) == strtolower($obj->getShippingCountry()) && isset(self::$vatRates[strtolower($vatCountry)])) {
                return self::$vatRates[strtolower($vatCountry)];
            } else {
                return 0;
            }
        }
        if (is_callable($getTaxRate)) {
            $taxRate = $getTaxRate($controller, $obj);
        }
        return $taxRate;
    }

    static function getTotalPriceOfItems($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    static function _getMaxPrice($shipping)
    {
        return self::_getMaxAttr($shipping, 'max_price');
    }

    static function _getMaxItems($shipping)
    {
        return self::_getMaxAttr($shipping, 'max_items');
    }

    static function _getMaxAttr($arr, $key)
    {
        $max = PHP_INT_MAX;
        if (is_array($arr) && isset($arr[$key])) {
            $max = $arr[$key];
        }
        return $max;
    }

    static function _applyGeneralShipping($controller, $obj, $cartInfo, $shippingRule)
    {
        $items = $cartInfo['items'];
        if (!isset($shippingRule)) {
            return NULL;
        }
        if (!$items) {
            return 0;
        }
        $val = 0;
        $shippingablePrice = self::getTotalPriceOfItems($items);
        if (isset($cartInfo['total_discount'])) {
            $shippingablePrice += $cartInfo['total_discount'];
        }
        $nitems = self::getTotalItems($items);
        $maxPrice = self::_getMaxPrice($shippingRule);
        $maxItems = self::_getMaxItems($shippingRule);
        if ($shippingablePrice >= $maxPrice || $nitems >= $maxItems) {
            $val = 0;
            if (isset($shippingRule['alternate'])) {
                $val = self::_applyGeneralShipping($controller, $obj, $cartInfo, $shippingRule['alternate']);
            }
        } else {
            if (is_callable($shippingRule)) {
                $formula = $shippingRule;
            }
            if (is_array($shippingRule) && isset($shippingRule['formula']) && is_callable($shippingRule['formula'])) {
                $formula = $shippingRule['formula'];
            }
            if (isset($formula)) {
                return $formula(self::getTotalItems($items), $items, $obj, $controller);
            }
            if (isset($shippingRule['overhead'])) {
                $val += $shippingRule['overhead'];
            }
            $totalItemsForShipping = self::_getItemQuantityForShipping($shippingRule, $items);
            if (isset($shippingRule['quantity_table'][$totalItemsForShipping])) {
                $val += $shippingRule['quantity_table'][$totalItemsForShipping];
            }
            if (isset($shippingRule['per_item_fixed'])) {
                $div = 1;
                if (!empty($shippingRule['item_group'])) {
                    $div = $shippingRule['item_group'];
                }
                $val += $shippingRule['per_item_fixed'] * ceil($totalItemsForShipping/$div);
            }
            if (isset($shippingRule['per_weight_fixed'])) {
                $totalWeight = self::getTotalWeightOfItems($items);
                if (!empty($shippingRule['weight_div'])) {
                    $totalWeight = ceil($totalWeight / $shippingRule['weight_div']);
                }
                $val += $shippingRule['per_weight_fixed'] * $totalWeight;
            }
        }
        if (isset($shippingRule['cascade'])) {
            $val += self::_applyGeneralShipping($controller, $obj, $cartInfo, $shippingRule['cascade']);
        }
        return $val;
    }

    static function _getBackorderItemsFromItems($items, $isReversed=FALSE)
    {
        $backItems = array_filter($items, function ($item) use ($isReversed) {return ($isReversed xor (isset($item['is_backorder'])&&$item['is_backorder'])); });
        return $backItems;
    }

    static function applyShipping($controller, $obj, $cartInfo, $shippingRule, $backorderShippingRule)
    {
        $backItems = self::_getBackorderItemsFromItems($cartInfo['items']);
        $normItems = self::_getBackorderItemsFromItems($cartInfo['items'], TRUE);
        $val = self::_applyGeneralShipping($controller, $obj, $cartInfo, $shippingRule);
        $vals = array($val, $val, NULL);
        if (isset($backorderShippingRule)) {
            $normCartInfo = $cartInfo;
            $backCartInfo = $cartInfo;
            $normCartInfo['items'] = $normItems;
            $backCartInfo['items'] = $backItems;
            $normShippingVal = self::_applyGeneralShipping($controller, $obj, $normCartInfo, $shippingRule);
            $backShippingVal = self::_applyGeneralShipping($controller, $obj, $backCartInfo, $backorderShippingRule);
            $val = $normShippingVal + $backShippingVal;
            $vals = array($val, $normShippingVal, $backShippingVal);
        }
        $isValid = TRUE;
        foreach ($vals as &$x) {
            if ($x < 0) {
                $x = 0;
                $isValid = FALSE;
            }
        }
        $shippingVals = array(
            'summary' => $vals[0],
            'details' => array(
                'normal' => $vals[1],
                'backorder' => $vals[2],
            ),
            'is_valid' => $isValid,
        );
        return $shippingVals;
    }

    static function getTotalWeightOfItems($items)
    {
        $total = array_sum(array_map(function ($x) {return $x['quantity'] * $x['weight'];}, $items));
        return $total;
    }

    static function getTotalItems($items)
    {
        $total = array_sum(array_map(function ($x) {return $x['quantity'];}, $items));
        return $total;
    }

    static function _getItemQuantityForShipping($shipping, $items)
    {
        $total = self::getTotalItems($items);
        return max($total, Helper_Structure::getArrayValue($shipping, 'min_quantity'));
    }

    static function _getSingpostZoneFromCountry($country)
    {
        /*
        if (!$country) {
            return NULL;
        }
        */
        $zones = array(
            'local' => array('SG'),
            'zone1' => array('MY', 'BN'),
            'zone2' => array(
                'AS', 'BD', 'BT', 'KH', 'CN', 'FJ', 'PF', 'GU', 'HK', 'IN',
                'ID', 'KI', 'KP', 'KR', 'LA', 'MO', 'MV', 'MH', 'FM', 'MN',
                'MM', 'NR', 'NP', 'NC', 'MP', 'PK', 'PW', 'PG', 'PH', 'PN',
                'WS', 'SB', 'LK', 'TW', 'TH', 'TL', 'TO', 'TV', 'VU', 'VN', 'WF',
            ),
        );
        $keys = array_keys(array_filter($zones, function ($a) use ($country) {return in_array($country, $a);}));
        $zone = 'zone3';
        if (isset($keys[0])) {
            $zone = $keys[0];
        }
        return $zone;
    }

    static function _getShippingCountry($controller)
    {
        $country = NULL;
        $member = $controller->getMemberObj();
        if ($member) {
            $country = $member->getCountry();
        } else if ($controller->getRequest('post', 'country')) {
            $country = $controller->getRequest('post', 'country');
        }
        return $country;
    }

    static function _getShippingSingpost($singpost, $controller, $shippingDest)
    {
        $shipping = NULL;
        $zone = self::_getSingpostZoneFromCountry($shippingDest);
        if (isset($singpost[$zone])) {
            $shipping = $singpost[$zone];
        }
        return $shipping;
    }

    static function _getShippingGeneral($shipping, $shippingDest)
    {
        $arr = NULL;
        $key = $shippingDest;
        if (isset($shipping[$key])) {
            $arr = $shipping[$key];
        }
        return $arr;
    }

    static function _getShippingGroups($shipping, $shippingDest)
    {
        $arr = NULL;
        $key = 'others';
        foreach ($shipping['groups'] as $thekey => $group) {
            if (in_array($shippingDest, $group)) {
                $key = $thekey;
                break;
            }
        }
        if (isset($shipping[$key])) {
            $arr = $shipping[$key];
        }
        return $arr;
    }

    static function getIfFilled($val)
    {
        if (isset($val) && $val) {
            return $val;
        }
        return NULL;
    }

    static function shippingRuleToArr($controller, $obj)
    {
        $rule = array(
            'overhead' => self::getIfFilled($obj->getOverhead()),
            'per_item_fixed' => self::getIfFilled($obj->getPerItem()),
            'per_weight_fixed' => self::getIfFilled($obj->getPerWeight()),
            'max_price' => self::getIfFilled($obj->getMaxPrice()),
            'max_items' => self::getIfFilled($obj->getMaxQuantity()),
        );
        if ($itemGroup = $obj->getItemGroup()) {
            $rule['item_group'] = $itemGroup;
        }
        if ($predefName = $obj->getPredefinedFormula()) {
            $predefinedFormulas = $controller->getKrcoConfigValue('cart', 'predefined_shipping_formulas');
            if ($predefinedFormulas && ($predefFormula = Helper_Structure::getArrayValue($predefinedFormulas, $predefName))) {
                if (is_array($rule) && is_array($predefFormula)) {
                    $rule = $predefFormula + $rule;
                } else {
                    $rule = $predefFormula;
                }
            }
        }
        return $rule;
    }

    static function _assignShippingRuleArr(&$asignee, $rule)
    {
        if (!($asignee)) {
            $asignee = array();
        }
        if (is_array($rule) && is_array($asignee)) {
            if (empty($asignee['_is_occupied'])) {
                $asignee += $rule;
                $asignee['_is_occupied'] = TRUE;
            } else {
                if (!isset($asignee['cascade'])) {
                    $asignee['cascade'] = array();
                }
                $newAssignee =& $asignee['cascade'];
                self::_assignShippingRuleArr($newAssignee, $rule);
            }
        } else {
            $asignee = $rule;
        }
    }

    static function getShippingRulesFromRuleObjs($controller, $objs)
    {
        $rules = NULL;
        if ($objs) {
            $rules = array(
                'type' => 'general',
            );
        }
        foreach ($objs as $obj) {
            $rule = self::shippingRuleToArr($controller, $obj);
            $key = $obj->getApplyToShippingMethod();
            if ($zone = $obj->getApplyToZone()) {
                $rules[$key]['type'] = 'singpost';
                if (!isset($rules[$key][$zone])) {
                    $rules[$key][$zone] = array();
                }
                $asignee =& $rules[$key][$zone];
            } else {
                if (!isset($rules[$key])) {
                    $rules[$key] = array();
                }
                $asignee =& $rules[$key];
            }
            $special = $obj->getMemberType();
            if ($special) {
                $asignee =& $asignee["special_$special"];
            }
            self::_assignShippingRuleArr($asignee, $rule, NULL);
        }
        return $rules;
    }

    static function getShippingRulesFromDb($controller)
    {
        $rules = array();
        $objs = $controller->getObjects('shipping_rules', 'getAllShippingRules', array());
        if ($objs) {
            $rules = self::getShippingRulesFromRuleObjs($controller, $objs);
            return $rules;
        }
        return NULL;
    }

    static function _getGeneralShippingRule($controller, $cartObj, $key, $fieldName)
    {
        $shipping = NULL;
        if (!is_null($controller->getKrcoConfigValue('cart', $key))) {
            $shipping = $controller->getKrcoConfigValue('cart', $key);
        } else if ($controller->getKrcoConfigVersion() >= 3) {
            $shipping = self::getShippingRulesFromDb($controller);
        }
        if ($shipping) {
            $shipArr = self::_getShippingArr($shipping, $controller, array(
                Helper_Structure::getObjCall($cartObj, 'get' . $fieldName),
                Helper_Structure::getObjCall($cartObj, 'getShippingCountry'),
                Helper_Structure::getObjCall($cartObj, 'getShippingCity'),
            ));
            $chooseSpecial = $controller->getKrcoConfigValue('cart', 'choose_special');
            if ($chooseSpecial && !is_null($special = $chooseSpecial($controller, $shipArr))) {
                $shipArr = $special;
            }
            $appliedLevel = '[empty]';
            if (($member = $controller->getMemberObj()) && ($level = $member->getMemberLevel())) {
                $appliedLevel = $level;
            }
            if (is_array($shipArr) && isset($shipArr["special_$appliedLevel"])) {
                $shipArr = $shipArr["special_$appliedLevel"];
            }
            return $shipArr;
        }
        return $shipping;
    }

    static function getBackorderShippingRule($controller, $cartObj)
    {
        $key = 'shipping';
        if (!is_null($controller->getKrcoConfigValue('cart', 'backorder_shipping'))) {
            $key = 'backorder_shipping';
        }
        $rule = self::_getGeneralShippingRule($controller, $cartObj, $key, 'BackorderShippingDestination');
        return $rule;
    }

    static function getShippingRule($controller, $cartObj)
    {
        return self::_getGeneralShippingRule($controller, $cartObj, 'shipping', 'ShippingDestination');
    }

    static function getFedexFormula($params, $controller, $shippingMethod, $shippingCountry, $shippingCity)
    {
        $origin = $controller->getDepConfigValue('shipping_origin_country');
        $formula = function ($n, $items, $cart, $controller) use ($params, $origin, $shippingCountry) {
            $weight = Helper_Cart::getTotalWeightOfItems($items);
            $price = $controller->shipping_table->getFedexPrice($weight, $origin, $shippingCountry, Helper_Structure::getArrayValue($params, 'fedex_type'));
            return $price;
        };
        return $formula;
    }

    static function getPosInternationalFormula($params, $controller, $shippingMethod, $shippingCountry, $shippingCity)
    {
        $origin = $controller->getDepConfigValue('shipping_jne_origin');
        $formula = function ($n, $items, $cart, $controller) use ($params, $origin, $shippingCountry) {
            $weight = Helper_Cart::getTotalWeightOfItems($items);
            $price = $controller->shipping_table->getPosInternationalPrice($weight, $origin, $shippingCountry, Helper_Structure::getArrayValue($params, 'pos_type'));
            return $price;
        };
        return $formula;
    }

    static function getSingpostInternationalFormula($params, $controller, $shippingMethod, $shippingCountry, $shippingCity)
    {
        $formula = function ($n, $items, $cart, $controller) use ($shippingCountry) {
            $weight = Helper_Cart::getTotalWeightOfItems($items);
            $zone = Helper_Cart::_getSingpostZoneFromCountry($shippingCountry);
            $fee = 0;
            if ($zone == 'zone1') {
                $steps = array(
                    20 => 0.50,
                    50 => 0.70,
                    100 => 1.10,
                );
                foreach ($steps as $step => $val) {
                    if ($weight <= $step) {
                        $fee = $val;
                        break;
                    }
                }
                if (!$fee) {
                    $fee = 1.10 + ceil(($weight-100)/100)*1.10;
                }
            } else if ($zone == 'zone2') {
                if ($weight <= 20) {
                    $fee = 0.70;
                }
                if (!$fee) {
                    $fee = 0.70 + ceil(($weight-20)/10)*0.25;
                }
            } else {
                if ($weight <= 20) {
                    $fee = 1.30;
                }
                if (!$fee) {
                    $fee = 1.30 + ceil(($weight-20)/10)*0.35;
                }
            }
            return 2.2 + $fee;
        };
        return $formula;
    }

    static function getJneShipArr($params, $controller, $shippingMethod, $shippingCountry, $shippingCity)
    {
        $origin = self::getJneOriginCity($controller, $params);
        $pw = $controller->shipping_table->getAdvancedJnePrice($origin, $shippingCity, Helper_Structure::getArrayValue($params, 'jne_type'));
        $shipArr = array(
            'per_weight_fixed' => $pw,
            'weight_div' => 1000,
        );
        if ($pw < 0) {
            $shipArr['overhead'] = -1000000;
        }
        return $shipArr;
    }

    static function getTikiShipArr($params, $controller, $shippingMethod, $shippingCountry, $shippingCity)
    {
        $origin = self::getJneOriginCity($controller, $params);
        $type = Helper_Structure::getArrayValue($params, 'tiki_type');
        $secs = $controller->shipping_table->getElapsedSecondsSinceLastUpdate($origin, $shippingCity, $type);
        
        $expirationTime = $controller->getKrcoConfigValue('cart','tiki_price_expiry');
        if (is_null($secs) || ($secs > $expirationTime)) {
            $cityIds = $controller->shipping_table->getTikiCityIds(array($origin,$shippingCity));

            // only update if city ids found
            if (count($cityIds) == 2 && isset($cityIds[0]) && isset($cityIds[1])) {                
                try {
                    // set timeout based on existing data: new ones get 15 seconds while existing ones get 1 second
                    // exiting ones can fallback to existing price data, but new ones can't, hence the difference
                    $controller->http->setTimeout(is_null($secs) ? 15 : 1);
                    $prices = $controller->http->get(
                        self::$TIKI_API_BASE_URL,
                        array(
                            'method'      => 'tariff',
                            'origin'      => $cityIds[0],
                            'destination' => $cityIds[1],
                            'weight'      => 1,
                        )
                    );
                    $error = $controller->http->getLastError();
                    if ($error != '') {
                        throw new Exception($error);
                    }

                    $prices = json_decode($prices,true);
                    $prices = $prices['tariff'];
                    $newPrices = array();
                    foreach ($prices as $price) {
                        $newPrices[$price['SERVICE']] = $price['TARIFF'];
                    }
                    $updateOK = $controller->shipping_table->updateTikiPrices($origin, $shippingCity, $newPrices);
                    if (!$updateOK) {
                        $controller->shipping_table->insertTikiPrices($origin, $shippingCity, $newPrices);
                    }
                } catch (Exception $e) {
                    echo implode(PHP_EOL,Helper_Exception::jTraceEx($e)).PHP_EOL;
                }
            }
        }
        $pw = $controller->shipping_table->getTikiPrice($origin, $shippingCity, $type);
        $shipArr = array(
            'per_weight_fixed' => $pw,
            'weight_div' => 1000,
        );
        if ($pw < 0) {
            $shipArr['overhead'] = -1000000;
        }
        return $shipArr;
    }

    static function getJneOriginCity($controller, $params)
    {
        $origin = Helper_Structure::getArrayValue($params, 'origin');
        if (!$origin) {
            $origin = $controller->getDepConfigValue('shipping_jne_origin');
        }
        if (!$origin) {
            $origin = 'Jakarta';
        }
        return $origin;
    }

    static function _getShippingArr($shipping, $controller, $shippingDests, $idx=0)
    {
        if (is_array($shipping)) {
            $ship = $shipping;
            if (isset($shipping['type'])) {
                if ($shipping['type'] == 'singpost') {
                    $ship = self::_getShippingSingpost($shipping, $controller, $shippingDests[$idx]);
                }
                if ($shipping['type'] == 'general') {
                    $ship = self::_getShippingGeneral($shipping, $shippingDests[$idx]);
                }
                if ($shipping['type'] == 'groups') {
                    $ship = self::_getShippingGroups($shipping, $shippingDests[$idx]);
                }
                if ($shipping['type'] == 'jne') {
                    $ship = self::getJneShipArr($shipping, $controller, $shippingDests[0], $shippingDests[1], $shippingDests[2]);
                }
                if ($shipping['type'] == 'tiki') {
                    $ship = self::getTikiShipArr($shipping, $controller, $shippingDests[0], $shippingDests[1], $shippingDests[2]);
                }
                if ($shipping['type'] == 'pos_international') {
                    $ship = $shipping;
                    unset($ship['type']);
                    $ship['formula'] = self::getPosInternationalFormula($shipping, $controller, $shippingDests[0], $shippingDests[1], $shippingDests[2]);
                }
                if ($shipping['type'] == 'singpost_international') {
                    $ship = $shipping;
                    unset($ship['type']);
                    $ship['formula'] = self::getSingpostInternationalFormula($shipping, $controller, $shippingDests[0], $shippingDests[1], $shippingDests[2]);
                }
                if ($shipping['type'] == 'fedex') {
                    $ship = $shipping;
                    unset($ship['type']);
                    $ship['formula'] = self::getFedexFormula($shipping, $controller, $shippingDests[0], $shippingDests[1], $shippingDests[2]);
                }
                if (is_array($ship) && isset($ship['type'])) {
                    $ship = self::_getShippingArr($ship, $controller, $shippingDests, $idx+1);
                }
            }
            if (isset($shipping['cascade'])) {
                $ship['cascade'] = self::_getShippingArr($shipping['cascade'], $controller, $shippingDests, $idx);
            }
            return $ship;
        }
        return $shipping;
    }

    static function _getAggregatorArrFromAgg($agg, $controller)
    {
        $aggArr = NULL;
        if ($agg) {
            $aggArr = $controller->productToArr($agg);
        }
        return $aggArr;
    }

    static function _getKrcoConfigProducts($controller)
    {
        $index = 'products';
        if (isset($controller->productsIndex)) {
            $index = $controller->productsIndex;
        }
        $configProducts = $controller->getKrcoConfigValue($index);
        return $configProducts;
    }

    static function getAggregator($product, $controller)
    {
        $agg = NULL;
        if (is_callable(array($product, 'getAggregatorId'))) {
            $aggId = $product->getAggregatorId();
            $krcoConfigProducts = self::_getKrcoConfigProducts($controller);
            $productDb = $krcoConfigProducts['db'];
            $getById = 'get' . $krcoConfigProducts['db_name'] . 'ById';
            $agg = $controller->getSingleObject($productDb, $getById, array($aggId));
        }
        return $agg;
    }

    static function getPriceCalculatorOfProduct($controller, $product)
    {
        $calculator = NULL;
        if ((method_exists($product, 'getAdvancedPricingRule')) && $advancedRules = json_decode($product->getAdvancedPricingRule(), TRUE)) {
            $calculator = function ($controller, $cartItem, $member, $product) use ($advancedRules) {
                return Helper_ProductPricing::calculateAdvancedProductPrice($controller, $cartItem, $member, $product, $advancedRules);
            };
        } else if (isset($product->__calculatePrice)) {
            $calculator = $product->__calculatePrice;
        } else if (!is_null($controller->getKrcoConfigValue('cart', 'price_calculator'))) {
            $calculator = $controller->getKrcoConfigValue('cart', 'price_calculator');
        }
        return $calculator;
    }

    static function _calculateProductPrice($controller, $product, $agg, $cartItem)
    {
        $calculator = self::getPriceCalculatorOfProduct($controller, $product);
        if ($calculator) {
            $member = $controller->getMemberObj();
            $price = $calculator($controller, $cartItem, $member, $product);
            if (isset($price)) {
                return Helper_String::sanitizeNumber($price);
            }
        }
        $getter = function ($x) use ($controller) {
            return $controller->getProductPrice($x);
        };
        $price = Helper_Structure::_getAttrWithGetter($getter, $product, $agg);
        return $price;
    }

    static function _cartProductToArr($product, $controller, $cartItem)
    {
        $productArr = $controller->productToArr($product);
        $segment = $controller->getKrcoConfigValue('cart', 'segment');
        $itemLongId = $cartItem->getLongId();
        $link = $productArr['link'];
        $agg = NULL;
        $aggArr = NULL;
        if ((method_exists($product, 'getProductType')) && ($product->getProductType() == 'simple')) {
            $agg = self::getAggregator($product, $controller);
            if (!isset($agg)) {
                return NULL;
            }
            $aggArr = self::_getAggregatorArrFromAgg($agg, $controller);
            $link = $aggArr['link'];
        }
        $images = Helper_Structure::_getAttrFromArr('detailed_images', $productArr, $aggArr);
        $itemImage = self::getCartItemImage($controller, $product, $images);
        $arr = array(
            'fid' => $cartItem->getProductFid(),
            'name' => $productArr['title'],
            'item_label' => $productArr['label'],
            'price' => self::_calculateProductPrice($controller, $product, $agg, $cartItem),
            'is_sale' => !empty($productArr['usual_price_raw']),
            'product_code' => Helper_Structure::_getAttrFromObj('ProductCode', $product),
            'quantity' => $cartItem->getQuantity(),
            'weight' => Helper_Structure::_getAttrFromObj('Weight', $product, $agg),
            'is_backorder' => Helper_Structure::_getAttrFromObj('IsAllowBackorder', $product, $agg),
            'is_free_shipping' => Helper_Structure::_getAttrFromObj('IsFreeShipping', $product),
            'link' => $link,
            'edit_link' => $controller->composeLink("/$segment/item/$itemLongId"),
            'image' => $itemImage,
            'options' => $cartItem->getOptions(),
        );
        return $arr;
    }

    static function getCartItemImage($controller, $product, $images)
    {
        $url = isset($images[0]['url']) ? $images[0]['url'] : NULL;
        $map = $controller->getKrcoConfigValue('products', 'optionMap');
        $titleMap = array();
        if (is_array($map)) {
            foreach ($map as $key => $val) {
                $titleKey = $controller->getProductOptionTitleByKey($key);
                $titleMap[strtolower($titleKey)] = $val;
            }
        }
        foreach ($images as $img) {
            if (Helper_Krco::isOptionsCorrectWithMap($titleMap, $product, $img)) {
                $url = $img['url'];
                break;
            }
        }
        return $url;
    }

    static function _createObjByFid($fid, $controller)
    {
        $krcoConfigProducts = self::_getKrcoConfigProducts($controller);
        $creator = $krcoConfigProducts['create_by_fid'];
        $obj = $creator($fid);
        $db = $krcoConfigProducts['db'];
        $addMethod = 'add' . $krcoConfigProducts['db_name'];
        $controller->dbAddObject($db, $addMethod, array($obj));
        return $obj;
    }

    static function _getProductObjByFid($controller, $productDb, $getMethod, $prodId)
    {
        $product = $controller->getSingleObject($productDb, $getMethod, array($prodId));
        $krcoConfigProducts = self::_getKrcoConfigProducts($controller);
        if (!$product) {
            if (isset($krcoConfigProducts['create_by_fid'])) {
                $obj = self::_createObjByFid($prodId, $controller);
                return $obj;
            }
            return NULL;
        }
        return $product;
    }

    static function _getProductFromItem($cartItem, $controller)
    {
        $prodId = $cartItem->getProductFid();
        $product = self::_getAggProdObjById($controller, $prodId);
        return $product;
    }

    static function cartItemToArr($cartItem, $controller)
    {
        $product = self::_getProductFromItem($cartItem, $controller);
        if (!isset($product)) {
            return NULL;
        }
        return self::_cartProductToArr($product, $controller, $cartItem);
    }

    static function _getProductCodeOfSoldProduct($soldProduct)
    {
        $productCode = Helper_Structure::_getAttrFromObj('ProductCode', $soldProduct);
        return $productCode;
    }

    static function formatSoldProductText($soldProduct, $currency)
    {
        $name = $soldProduct->getTitle();
        $qty = $soldProduct->getQuantity();
        $price = Helper_String::dollarFormat($soldProduct->getPrice() * $qty, 2, $currency);
        $productCode = self::_getProductCodeOfSoldProduct($soldProduct);
        $s = "$qty x $name";
        if ($productCode) {
            $s .= " ($productCode)";
        }
        if (!is_null($soldProduct->getPrice())) {
            $s .= " = $price";
        }
        return $s;
    }

    static function _soldProductToCartItem($sold)
    {
        $item = array(
            'ordered_id' => $sold->getId(),
            'fid' => $sold->getProductFid(),
            'name' => $sold->getTitle(),
            'product_code' => Helper_Structure::_getAttrFromObj('ProductCode', $sold),
            'image' => Helper_Structure::_getAttrFromObj('ImageUrl', $sold),
            'quantity' => $sold->getQuantity(),
            'price' => $sold->getPrice(),
        );
        return $item;
    }

    static function soldProductsToCartItems($solds)
    {
        $items = array();
        foreach ($solds as $sold) {
            $item = self::_soldProductToCartItem($sold);
            $items[] = $item;
        }
        return $items;
    }

    static function _orderToCartForPaypal($order)
    {
        $orderedProducts = $order->getOrderedProducts();
        $cart = array(
            'items' => Helper_Cart::soldProductsToCartItems($orderedProducts),
            'shipping' => $order->getShippingCost(),
            'tax' => $order->getTax(),
            'shipping_discount' => 0,
            'discounts' => array(
                array(
                    'value' => $order->getDiscount(),
                ),
            ),
        );
        return $cart;
    }

    static function getCurrencyConversionRate($controller, $from, $to)
    {
        if (isset($controller->currency_conversions)) {
            $rate = $controller->currency_conversions->getCurrencyConversionRate($from, $to);
            return $rate;
        }
    }

    static function orderToPaypalParams($controller, $order, $member, $transId, $orderType=NULL)
    {
        if ($controller->getKrcoConfigValue('cart', 'with_paypal_convert_currency')) {
            $controller->_activeCurrency = Helper_Cart::getActiveCurrency($controller->_getCurrenciesFromConf($controller->getRawCurrencies()));
        }
        if ($order->getCurrency() == 'IDR') {
            $controller->_activeCurrency = array(
                'title' => 'United States Dollar',
                'symbol' => 'US$',
                'currency' => 'USD',
                'exchange_rate' => self::getCurrencyConversionRate($controller, 'IDR', 'USD'),
            );
        }
        $cart = self::_orderToCartForPaypal($order);
        $orderId = $order->getLongId();
        $params['currency_code'] = $order->getCurrency();
        if (isset($controller->_activeCurrency['currency'])) {
            $params['currency_code'] = $controller->_activeCurrency['currency'];
        }
        $params = self::cartToPaypalParams($controller, $cart, $member) + $params;
        $params['charset'] = 'utf-8';
        //$params['custom'] = "$orderId;$orderType";
        $params['custom'] = $transId;
        $params['notify_url'] = $controller->composeLink(self::_getNotifyPath($controller, $orderType));
        $params['return'] = $controller->composeLink('');
        return $params;
    }

    static function _getNotifyPath($controller, $orderType)
    {
        $path = "/ipn";
        $useSpecialType = TRUE;
        if ($controller->getKrcoConfigVersion() >= 1) {
            $useSpecialType = FALSE;
        }
        if ($controller->getObjKrcoConfig('with_special_ipn')) {
            $useSpecialType = TRUE;
        }
        if ($orderType && $useSpecialType) {
            $path = "/${orderType}_ipn";
        }
        return $path;
    }

    static function cartToPaypalParams($controller, $cart, $member=NULL)
    {
        $custom = NULL;
        if (isset($cart['code'])) {
            $custom = $cart['code'];
        }
        $params = array(
            'cmd' => '_cart',
            'upload' => '1',
            'custom' => $custom,
            'notify_url' => $controller->composeLink('/ipn'),
        );
        $params += self::_cartToPaypalItemParams($controller, $cart);
        $params += self::_memberToPaypalParams($member);
        $params += self::getPaypalConfigIfApplicable($controller);
        if ($calc = self::getPaypalFeeCalculator($controller)) {
            $tax = $calc(self::_getConvertedPrice($controller, self::getGrandTotal($cart)));
            if (!isset($params['tax_cart'])) {
                $params['tax_cart'] = 0;
            }
            $params['tax_cart'] = self::_convertPrice($controller, 1, $tax);
        }
        return $params;
    }

    static function getPaypalFeeCalculator($controller)
    {
        $calc = $controller->getKrcoConfigValue('cart', 'paypal_fee_calculator');
        if ($controller->getDepConfigValue('paypal_fee_to_customer')) {
            $cartCurrency = $controller->getCartCurrency();
            $pff = 0.3;
            $pfp = 0.044;
            if ($cartCurrency == 'SGD') {
                $pff = 0.5;
                $pfp = 0.039;
            }
            $calc = function ($total) use ($pff, $pfp) {
                return ($total+$pff)/(1-$pfp) - $total;
            };
        }
        return $calc;
    }

    static function getPaypalConfig($controller)
    {
        $paypal = array();
        if (!is_null($controller->getKrcoConfigValue('cart', 'paypal'))) {
            $configPaypal = $controller->getKrcoConfigValue('cart', 'paypal');
            if (is_array($configPaypal)) {
                $paypal = $controller->getKrcoConfigValue('cart', 'paypal') + $paypal;
            } else {
                $paypal['business'] = $configPaypal;
            }
        }
        if ($depBusiness = $controller->getDepConfigValue('paypal_business')) {
            $paypal['business'] = $depBusiness;
        }
        return $paypal;
    }

    static function getPaypalConfigIfApplicable($controller)
    {
        $paypal = array();
        if ($controller->getLimiterValue('with_paypal') !== FALSE) {
            return self::getPaypalConfig($controller);
        }
        return $paypal;
    }

    static function _memberToPaypalParams($member)
    {
        $params = array();
        if (is_object($member)) {
            $params = array(
                'first_name' => $member->getFirstName(),
                'last_name' => $member->getLastName(),
                'address1' => $member->getAddressLine1(),
                'address2' => $member->getAddressLine2(),
                'zip' => $member->getPostalCode(),
                'city' => $member->getCity(),
                'state' => $member->getState(),
                'country' => $member->getCountry(),
                'night_phone_b' => $member->getPhoneNumber(),
                'email' => $member->getEmail(),
            );
        }
        return $params;
    }

    static function _addItemOptionStr(&$name, $item, $optionStr)
    {
        $name .= " ($optionStr)";
    }

    static function _getPaypalItemName($controller, $item)
    {
        $name = $item['name'];
        if ($controller && $controller->getObjKrcoConfig('with_options_in_paypal', 'cart')) {
            $optionStr = self::optionsToStr($item);
            self::_addItemOptionStr($name, $item, $optionStr);
        }
        return $name;
    }

    static function _getExchangeRate($controller, $convert)
    {
        $rate = $convert;
        if (is_array($convert)) {
            if (isset($convert['file'])) {
                $rate = 0;
                if (file_exists($convert['file'])) {
                    $rate = (double)(file_get_contents($convert['file']));
                }
            }
            if (isset($convert['from']) && isset($convert['to'])) {
                $rate = $controller->currency_conversions->getCurrencyConversionRate($convert['from'], $convert['to']);
                if (is_null($rate)) {
                    $rate = 1;
                }
            }
        }
        return $rate;
    }

    static function _convertPrice($controller, $convert, $price, $decimals=2)
    {
        $rate = self::_getExchangeRate($controller, $convert);
        $converted = round($rate * $price, $decimals);
        return $converted;
    }

    static function _getConvertedPrice($controller, $price)
    {
        $converted = round($price, 2);
        if ($controller) {
            $convert = $controller->getKrcoConfigValue('cart', 'currency_conversion');
            $decimals = 2;
            if (isset($controller->_activeCurrency['exchange_rate'])) {
                $convert = $controller->_activeCurrency['exchange_rate'];
            }
            if (isset($controller->_activeCurrency['decimals'])) {
                $decimals = $controller->_activeCurrency['decimals'];
            }
            if (isset($convert)) {
                $converted = self::_convertPrice($controller, $convert, $price, $decimals);
            }
        }
        return $converted;
    }

    static function _cartItemToPaypalParams($controller, $item, $i)
    {
        $params = array();
        $params["item_name_$i"] = self::_getPaypalItemName($controller, $item);
        if (isset($item['product_code']) && $item['product_code']) {
            $params["item_number_$i"] = $item['product_code'];
        }
        $params["amount_$i"] = self::_getConvertedPrice($controller, $item['price']);
        $params["quantity_$i"] = $item['quantity'];
        return $params;
    }

    static function _cartItemsToPaypalParams($controller, $items)
    {
        $params = array();
        $i = 1;
        foreach ($items as $item) {
            $itemParams = self::_cartItemToPaypalParams($controller, $item, $i);
            $params += $itemParams;
            $i++;
        }
        return $params;
    }

    static function _cartToPaypalItemParams($controller, $cart, $member=NULL)
    {
        $items = $cart['items'];
        $params = self::_cartItemsToPaypalParams($controller, $items);
        if (isset($cart['shipping']) && isset($cart['shipping_discount']) && $cart['shipping']) {
            $params['shipping_1'] = self::_getConvertedPrice($controller, $cart['shipping'] + $cart['shipping_discount']);
        }
        if (isset($cart['discounts']) && $cart['discounts']) {
            //$discRaw = array_sum(array_map(function ($x) {return $x['value'];}, $cart['discounts']));
            $discRaw = -1*self::getDiscountAmount($cart);
            $itemTotal = self::getItemTotal($cart['items']);
            if ($discRaw >= $itemTotal) {
                $discRaw = max(0, $itemTotal-0.01);
            }
            $discountAmountCart = Helper_Paypal::numberFormat($discRaw);
            if ($discountAmountCart) {
                $params['discount_amount_cart'] = self::_getConvertedPrice($controller, $discountAmountCart);
            }
        }
        if (isset($cart['tax']) && $cart['tax']) {
            $params['tax_cart'] = self::_getConvertedPrice($controller, $cart['tax']);
        }
        return $params;
    }

    static function getSoldProductsText($solds, $currency)
    {
        $itemsTexts = array_map(function ($x) use ($solds, $currency) {return '* ' . Helper_Cart::formatSoldProductText($x, $currency);}, $solds);
        $itemsText = implode("\n", $itemsTexts);
        return $itemsText;
    }

    static function getItemTitle($item, $withHtml=FALSE, $options=array(), $renderer=NULL, $cartRenderer=NULL)
    {
        $itemTitle = '';
        if (method_exists($renderer, 'renderCartItemDescription')) {
            $itemTitle = $renderer->renderCartItemDescription($item, $options);
        } else {
            if (isset($item['link'])) {
                $itemTitle = Helper_Xml::xmlSpan($item['name'], array('class' => 'cart-item-title'));
                if (!isset($cartRenderer->withProductLink) || $cartRenderer->withProductLink !== FALSE) {
                    $itemTitle = Helper_Xml::xmlA($item['name'], $item['link'], array('class' => 'cart-item-title'));
                }
            }
            $itemTitleRaw = $item['name'];
            $withBreak = !empty($options['with_break_options']);
            $optionStr = self::optionsToStr($item, $withBreak);
            if ($optionStr) {
                self::_addItemOptionStr($itemTitle, $item, $optionStr);
                self::_addItemOptionStr($itemTitleRaw, $item, $optionStr);
            }
            if (isset($options['with_edit_item']) && $options['with_edit_item']) {
                $itemTitle .= "\n";
                $itemTitle .= Helper_Xml::xmlA('edit', $item['edit_link'], array('class' => 'cart-item-edit'));
            }
        }
        if ($withHtml) {
            return $itemTitle;
        }
        return $itemTitleRaw;
    }

    static function optionsToStr($item, $withBreak=FALSE)
    {
        $options = Helper_Structure::getArrayValue($item, 'options');
        return self::optionArrToStr($options, $withBreak);
    }

    static function optionArrToStr($options, $withBreak)
    {
        $str = '';
        $i = 0;
        if (isset($options) && is_array($options)) {
            foreach ($options as $key => $val) {
                if ($val != '' && (strpos($key, '_') !== 0)) {
                    if ($str) {
                        $str .= ', ';
                        if ($withBreak) {
                            $str .= '<br />';
                        }
                    }
                    $str .= "$key: $val";
                }
                $i++;
            }
        }
        return $str;
    }

    static function _cartItemToSoldProduct($controller, $item, $soldProductType)
    {
        $quantity = $item['quantity'];
        $item_name = Helper_Cart::getItemTitle($item);
        $orderedId = Helper_Structure::getArrayValue($item, 'ordered_id');
        $sold = NULL;
        if ($controller) {
            $sold = $controller->getSingleObject('orders', 'getOrderedProductById', array($orderedId));
        }
        if (!$sold) {
            $sold = new $soldProductType();
        }
        $sold->setTitle($item_name);
        $sold->setQuantity($quantity);
        $sold->setPrice($item['price']);
        $sold->setProductFid($item['fid']);
        if (method_exists($sold, 'setImageUrl')) {
            $imageUrl = Helper_Structure::getArrayValue($item, 'image');
            if (!empty($item['options']['_image_url'])) {
                $imageUrl = $item['options']['_image_url'];
            }
            $sold->setImageUrl($imageUrl);
        }
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($item['options']["_attribute$i"])) {
                $setMethod = "setAttribute$i";
                $sold->$setMethod($item['options']["_attribute$i"]);
            }
        }
        if (method_exists($sold, 'setProductCode') && isset($item['product_code'])) {
            $sold->setProductCode($item['product_code']);
        }
        return $sold;
    }

    static function cartItemsToSoldProducts($controller, $items, $soldProductType)
    {
        $solds = array();
        if (is_array($items)) {
            foreach ($items as $item) {
                $sold = self::_cartItemToSoldProduct($controller, $item, $soldProductType);
                $solds[] = $sold;
            }
        }
        return $solds;
    }

    static function cartToSoldProducts($controller, $cart, $soldProductType)
    {
        $solds = self::cartItemsToSoldProducts($controller, $cart['items'], $soldProductType);
        return $solds;
    }

    static function composeOrderId($order, $format, $controller, $dbOrders=NULL)
    {
        if ($format) {
            if (is_array($format)) {
                return self::_composeSequenceOrderId($format, $controller, $dbOrders);
            }
        }
        $id = NULL;
        if (is_callable(array($order, 'getRecipientEmail'))) {
            $now = 0;
            if (isset($controller->now)) {
                $now = $controller->now;
            }
            $id = Helper_String::sumChars($order->getRecipientEmail()) . date('ymdhis', $now);
        }
        return $id;
    }

    static function _getPrefixFromPrefixString($prefixString, $controller)
    {
        $curYear = date('Y', $controller->now);
        $prefix = str_replace('[current_year]', $curYear, $prefixString);
        if ($prefixString == '_current_year') {
            $prefix = $curYear;
        }
        return $prefix;
    }

    static function _getPrefixFromPrefixFormat($prefixFunction, $controller)
    {
        $prefix = '';
        if (is_string($prefixFunction)) {
            $prefix = self::_getPrefixFromPrefixString($prefixFunction, $controller);
        } else if ($prefixFunction) {
            $prefix = $prefixFunction($controller);
        }
        return $prefix;
    }

    static function _composeSequenceId($format, $controller, $lastId)
    {
        $prefixFunction = $format['prefix'];
        $prefix = self::_getPrefixFromPrefixFormat($prefixFunction, $controller);
        $lastNumber = self::_getNumberPart($lastId, $prefix);
        $digits = $format['digits'];
        $minNumber = 0;
        if (isset($format['min_number'])) {
            $minNumber = $format['min_number'];
        }
        $newNumber = max($minNumber, $lastNumber+1);
        $number = sprintf("%0${digits}d", $newNumber);
        $id = $prefix . $number;
        return $id;
    }

    static function _composeSequenceOrderId($format, $controller, $ordersDb=NULL)
    {
        if (!$ordersDb) {
            $ordersDb = $controller->_getDbOrdersName();
        }
        $lastId = $controller->dbCall($ordersDb, 'getLastLongId', array(), TRUE);
        return self::_composeSequenceId($format, $controller, $lastId);
    }

    static function getItemTotal($items)
    {
        $itemTotal = 0;
        if ($items) {
            $itemTotal = array_sum(array_map(function ($item) {return Helper_Structure::getArrayValue($item, 'quantity') * Helper_Structure::getArrayValue($item, 'price');}, $items));
        }
        return $itemTotal;
    }

    static function getTotalDiscountOfDiscArrs($discounts)
    {
        $discRaw = array_sum(array_map(function ($disc) {return $disc['value'];}, $discounts));
        return $discRaw;
    }

    static function getDiscountAmount($cart)
    {
        $discounts = array();
        if (isset($cart['discounts'])) {
            $discounts = $cart['discounts'];
        }
        $itemTotal = self::getItemTotal($cart['items']);
        $shippingTotal = self::getTotalShipping($cart);
        $discRaw = self::getTotalDiscountOfDiscArrs($discounts);
        $compareTotal = $itemTotal + $shippingTotal;
        if (Helper_Structure::getArrayValue($cart, 'is_discount_ignore_shipping')) {
            $compareTotal = $itemTotal;
        }
        return max(-1*($compareTotal), $discRaw);
    }

    static function getTotalShipping($cart)
    {
        $cartArr = new PhpwebArray($cart);
        $shipping = $cartArr->get('shipping');
        $shippingDiscount = $cartArr->get('shipping_discount');
        return $shipping + $shippingDiscount;
    }

    static function getTaxableTotal($cart)
    {
        $itemTotal = self::getItemTotal($cart['items']) + self::getDiscountAmount($cart) + self::getTotalShipping($cart);
        $grandTotal = max(0, $itemTotal);
        return $grandTotal;
    }

    static function getGrandTotal($cart)
    {
        $taxableTotal = self::getTaxableTotal($cart);
        $taxVal = Helper_Structure::getArrayValue($cart, 'tax');
        return $taxableTotal + $taxVal;
    }

    static function getAmountSpent($cart)
    {
        $amountSpent = max(0, self::getItemTotal($cart['items']) + self::getDiscountAmount($cart));
        return $amountSpent;
    }

    static function _appendMoneyWithLabel($label, $currency, $val, $isOptional=FALSE)
    {
        $s = '';
        if (!$isOptional || $val) {
            $s = "$label: " . Helper_String::dollarFormat($val, 2, $currency) . "\n";
        }
        return $s;
    }

    static function getCartText($solds, $cartCode, $options)
    {
        $cart_text = '';
        $currency = $options['currency'];
        $discount = $options['discount'];
        $shipping = $options['shipping'];
        $total = $options['total'];
        $cart_text .= Helper_Cart::getSoldProductsText($solds, $currency) . "\n";
        $cart_text .= self::_appendMoneyWithLabel('Discount', $currency, $discount, TRUE);
        $cart_text .= self::_appendMoneyWithLabel('Shipping', $currency, $shipping, TRUE);
        $cart_text .= self::_appendMoneyWithLabel('Total', $currency, $total);
        return $cart_text;
    }

    static function clearCartByCode($cart_code, $controller)
    {
        $cartObj = $controller->getSingleObject('carts', 'getCartByCode', array($cart_code));
        if ($cartObj) {
            foreach ($cartObj->getCartItems() as $item) {
                self::_subtractProductStock($item, $controller);
            }
            $cartObj->setIsActive(FALSE);
            $controller->carts->updateCart($cartObj);
        }
    }

    static function _processLowStock($obj, $controller)
    {
        if (self::_isLowStock($obj, $controller)) {
            $curStock = $obj->getTotalInventory();
            $from = $controller->getNoReplyFrom();
            $to = $controller->getOrdersEmailAddress();
            $productName = $obj->getTitle();
            $subject = "[Warning] Low Product Stock: $productName";
            $signature = $controller->_getEmailSignature();
            $body = <<<EOD
<p>
Dear Admin,
</p>
<p>
Product "$productName" stock is low ($curStock).
</p>
$signature

EOD;
            Helper_Mail::sendEmail($controller, $from, $to, NULL, NULL, $subject, $controller->_getHtmlEmailBody($body));
        }
    }

    static function applySoldStockChange($obj, $q)
    {
        if (method_exists($obj, 'getSoldInventory')) {
            $stockSold = $obj->getSoldInventory() + $q;
            $obj->setSoldInventory($stockSold);
        }
    }

    static function _subtractProductObjStock($obj, $item, $controller, $isInverse, $configKey=NULL)
    {
        if ($controller->getObjKrcoConfig('track_inventory', $configKey) === FALSE) {
            return ;
        }
        if (!$obj) {
            return ;
        }
        $agg = NULL;
        $needsUpdate = FALSE;
        if (!is_null($controller->getKrcoConfigValue('cart', 'custom_track_inventory'))) {
            $tracker = $controller->getKrcoConfigValue('cart', 'custom_track_inventory');
            $tracker($obj, $item);
            $needsUpdate = TRUE;
        }
        if (is_callable(array($obj, 'getTotalInventory'))) {
            $qty = $obj->getTotalInventory();
            $q = $item->getQuantity();
            if ($isInverse) {
                $q *= -1;
            }
            $newStock = $obj->getTotalInventory() - $q;
            $obj->setTotalInventory($newStock);
            self::applySoldStockChange($obj, $q);
            if ($agg = self::getAggregator($obj, $controller)) {
                self::applySoldStockChange($agg, $q);
            }
            self::_processLowStock($obj, $controller);
            $needsUpdate = TRUE;
        }
        if ($needsUpdate) {
            $theConfigKey = 'products';
            if ($configKey) {
                $theConfigKey = $configKey;
            }
            $db_name = $controller->getKrcoConfigValue($theConfigKey, 'db');
            $update_method = 'update' . $controller->getKrcoConfigValue($theConfigKey, 'db_name');
            $controller->dbUpdateObject($db_name, $update_method, array($obj));
            Helper_Krco::afterUpdateProduct($controller, $obj);
            if (!empty($agg)) {
                $controller->dbUpdateObject($db_name, $update_method, array($agg));
            }
        }
    }

    static function _subtractProductStock($item, $controller, $isInverse=NULL)
    {
        if ($controller->getKrcoConfigValue('cart', 'track_inventory')) {
            $db_name = $controller->getKrcoConfigValue('products', 'db');
            $get_method = 'getManageableItemByFriendlyId';
            $obj = $controller->getSingleObject($db_name, $get_method, array($item->getProductFid()), TRUE);
            self::_subtractProductObjStock($obj, $item, $controller, $isInverse);
        }
    }

    static function _isLowStock($obj, $controller)
    {
        $min = self::_getMinLowStock($controller);
        $isLow = ($min) && ($obj->getTotalInventory() <= $min);
        return $isLow;
    }

    static function _getMinLowStock($controller)
    {
        $min = '5';
        if ($controller->getKrcoConfigVersion() >= 3) {
            $min = 0;
        }
        if (!is_null($krcoMin = $controller->getKrcoConfigValue('cart', 'stock_warning_min'))) {
            $min = $krcoMin;
        }
        if ($depMin = $controller->getDepConfigValue('stock_warning_min')) {
            $min = $depMin;
        }
        return $min;
    }

    static function _generatePaypalUrlFromParams($params, $controller)
    {
        $paypalBase = 'https://www.paypal.com/cgi-bin/webscr';
        if (($controller) && !is_null($controller->getKrcoConfigValue('cart', 'paypal_url'))) {
            $paypalBase = $controller->getKrcoConfigValue('cart', 'paypal_url');
        }
        if (($controller) && !empty($controller->deployment_config['paypal_url'])) {
            $paypalBase = $controller->deployment_config['paypal_url'];
        }
        $paypalUrl = $paypalBase . '?' . http_build_query($params);
        return $paypalUrl;
    }

    static function _getNumberPart($s, $prefix)
    {
        if (!$prefix) {
            return $s;
        }
        if (strpos($s, $prefix) !== 0) {
            return 0;
        }
        $start = strlen($prefix);
        $number = substr($s, $start);
        return $number;
    }

    static function getTotalCostOfOrder($obj)
    {
        return max(0, Helper_Cart::getItemTotal(Helper_Cart::soldProductsToCartItems($obj->getOrderedProducts())) + $obj->getShippingCost() + $obj->getDiscount() + $obj->getTax());
    }

    static function getItemTotalCostOfOrder($obj)
    {
        return max(0, Helper_Cart::getItemTotal(Helper_Cart::soldProductsToCartItems($obj->getOrderedProducts())));
    }

    static function validateCartStock($controller, $cartObj)
    {
        if (is_null($cartObj)) {
            return TRUE;
        }
        $lang = $controller->lang;
        $items = $cartObj->getCartItems();
        $nItems = 0;
        foreach ($items as $item) {
            $q = $item->getQuantity();
            $prodObj = self::_getAggProdObjById($controller, $item->getProductFid());
            if ($prodObj) {
                $nItems ++;
                $min = self::_getMinOrder($controller, $prodObj);
                if ($q < $min) {
                    $product_name = $prodObj->getTitle($lang);
                    $controller->last_error = "$product_name must have a minimum order of $min.";
                    return FALSE;
                }
                if (!self::_isStockValid($controller, $prodObj, $item)) {
                    $cartObj->removeItemByProdFid($prodObj->getFriendlyId(), $item->getOptions());
                    return FALSE;
                }
            }
        }
        if (!empty($controller->last_error)) {
            return FALSE;
        }
        if (!$nItems) {
            $controller->last_error = "Your shopping cart is empty.";
            return FALSE;
        }
        return TRUE;
    }

    static function _combineSimpleFromAggregator($simple, $agg)
    {
        $attrs = array('Weight', 'IsAllowBackorder', 'IsIgnoreStock', 'SomeDummy', 'Label', 'Price', 'SalePrice', 'AdvancedPricingRule');
        $newSimple = NULL;
        if ($simple) {
            $newSimple = clone $simple;
        }
        foreach ($attrs as $attr) {
            $val = Helper_Structure::_getAttrFromObj($attr, $newSimple, $agg);
            $setMethod = 'set' . $attr;
            if (is_callable(array($newSimple, $setMethod))) {
                $newSimple->$setMethod($val);
            }
        }
        return $newSimple;
    }

    static function _getAggProdObjById($controller, $id)
    {
        $prod = self::getProdObjById($controller, $id);
        $agg = self::getAggregator($prod, $controller);
        $newProd = self::_combineSimpleFromAggregator($prod, $agg);
        return $newProd;
    }

    static function getProdObjById($controller, $id)
    {
        $db_name = $controller->getKrcoConfigValue('products', 'db');
        $get_method = 'get' . $controller->getKrcoConfigValue('products', 'db_name') . 'ByFriendlyId';
        $obj = Helper_Cart::_getProductObjByFid($controller, $db_name, $get_method, $id);
        if (!isset($obj) && ($getByFid = $controller->getKrcoConfigValue('products', 'get_obj_by_fid'))) {
            $obj = $getByFid($controller, $id);
        }
        return $obj;
    }

    static function _getMinOrder($controller, $prodObj)
    {
        return Helper_Krco::getObjAttributeWithDefault($controller, $prodObj, 'MinOrder', 'products');
    }

    static function _isStockValid($controller, $prodObj, $item)
    {
        $q = 0;
        if (is_object($item)) {
            $q = $item->getQuantity();
        }
        if (!is_null($controller->getKrcoConfigValue('cart', 'custom_is_stock_valid'))) {
            $isStockValid = $controller->getKrcoConfigValue('cart', 'custom_is_stock_valid');
            return self::_customIsStockValid($controller, $isStockValid, $prodObj, $item);
        }
        $max = $controller->getObjKrcoConfig('max_quantity', 'cart');
        if (($max = $controller->getObjKrcoConfig('max_quantity', 'cart')) && ($q > $max)) {
            $controller->last_error = "Cannot add more than $max items to cart. Please contact us to order in bulk quantity.";
            return FALSE;
        }
        if (!is_callable(array($prodObj, 'getTotalInventory'))) {
            return TRUE;
        }
        $stock = $prodObj->getTotalInventory();
        if (self::_isIgnoreStock($prodObj)) {
            return TRUE;
        }
        $product_name = $prodObj->getTitle($controller->lang);
        if (self::_isStockEmpty($controller, $prodObj)) {
            $memberEmail = '';
            $memberObj = $controller->getMemberObj();
            if ($memberObj) {
                $memberEmail = $memberObj->getEmail();
            }
            if ($notifyEmail = $controller->getRequest('post', 'notify_email')) {
                $memberEmail = $notifyEmail;
            }
            if ($memberEmail) {
                self::_addWatch($controller, $memberObj, $prodObj, $notifyEmail);
                $controller->last_error = "$product_name is currently not available in stock. You will be notified ($memberEmail) when the item is available.";
                $controller->last_error_type = 'success';
            } else {
                $controller->last_error = "$product_name is currently not available in stock.";
            }
            return FALSE;
        }
        if (($stock > 0) && ($q > $stock)) {
            //$item->setQuantity($stock);
            //$controller->_successMessage = "$product_name does not have enough stock for $q items, $stock items has been added successfully.";
            //return TRUE;
            $controller->last_error = "$product_name does not have enough stock for $q items. Kindly amend quantity and continue.";
            if ($customMessage = $controller->getKrcoConfigValue('cart', 'out_of_stock_message')) {
                $controller->last_error = $customMessage($product_name, $q);
            }
            return FALSE;
        }
        if ($q < ($min = self::_getMinOrder($controller, $prodObj))) {
            $controller->last_error = "$product_name must have a minimum order of $min.";
            return FALSE;
        }
        return TRUE;
    }

    static function _isAllowBackorder($obj)
    {
        $allow = FALSE;
        if (method_exists($obj, 'getIsAllowBackorder')) {
            $allow = $obj->getIsAllowBackorder();
        }
        return $allow;
    }

    static function _isIgnoreStock($obj)
    {
        $allow = FALSE;
        if (method_exists($obj, 'getIsIgnoreStock')) {
            $allow = $obj->getIsIgnoreStock();
        }
        return $allow;
    }

    static function _isStockEmpty($controller, $prodObj)
    {
        return ($prodObj->getTotalInventory() <= 0) && !$controller->getObjKrcoConfig('is_ignore_empty_stock', 'cart');
    }

    static function _customIsStockValid($controller, $isStockValid, $prodObj, $item)
    {
        $ret = $isStockValid($controller, $prodObj, $item);
        if ($ret === TRUE) {
            return TRUE;
        }
        $controller->last_error = $ret;
        return FALSE;
    }

    static function _addWatch($controller, $memberObj, $prodObj, $notifyEmail)
    {
        $watch = new Watch();
        $watch->setItemId($prodObj->getId());
        if ($memberObj) {
            $memberId = $memberObj->getId();
        }
        if ($notifyEmail) {
            $memberId = $notifyEmail;
        }
        $watch->setMemberId($memberId);
        $controller->dbAddObject('watches', 'addWatch', array($watch));
    }

    static function _getInvoiceId($controller, $dbOrdersName, $format)
    {
        if (isset($controller->$dbOrdersName) && method_exists($controller->$dbOrdersName, 'getLastInvoiceId')) {
            $lastId = $controller->dbCall($dbOrdersName, 'getLastInvoiceId', array(), TRUE);
            $id = Helper_Cart::_composeSequenceId($format, $controller, $lastId);
            return $id;
        }
        return NULL;
    }

    static function isOrderNotPaid($order)
    {
        $orderStatus = $order->getOrderStatus();
        if (in_array($order->getOrderStatus(), array('Pending', 'Verifying Payment', 'Pending and Verified'))) {
            return TRUE;
        }
        return FALSE;
    }

    static function processOrderPaid($controller, $order, $key_pl)
    {
        if (self::isOrderNotPaid($order)) {
            self::_setOrderStatusPaid($controller, $order, $key_pl);
            $memberId = $order->getMemberId();
            $member = $controller->getSingleObject('members', 'getMemberById', array($memberId));
            self::_setMemberPoint($controller, $order, $member);
            return TRUE;
        }
        return FALSE;
    }

    static function _setOrderStatusPaid($controller, $order, $key_pl)
    {
        $paidStatus = 'Processing';
        if (!is_null($controller->getKrcoConfigValue($key_pl, 'order_paid_status'))) {
            $paidStatus = $controller->getKrcoConfigValue($key_pl, 'order_paid_status');
        }
        $order->setOrderStatus($paidStatus);
        $ordersDb = $controller->getKrcoConfigValue($key_pl, 'db_orders');
        if (!$ordersDb) {
            throw new Exception('Unknown order type.');
        }

        $id = $order->getLongId();
        $controller->$ordersDb->updateOrder($order);
        $memberObj = $controller->getSingleObject('members', 'getMemberById', array($order->getMemberId()));
        if ($callback = $controller->getKrcoConfigValue('cart', 'callback_order_paid')) {
            $callback($controller, $order, $memberObj);
        }
        $controller->raiseSystemEvent('member_purchase', array(
            'member' => $memberObj,
            'order' => $order,
        ));
        if ($key_pl == 'subscribe') {
            Helper_Subscription::updateSubscriptionOfOrder($controller, $order);
        }
        $orderId = $order->getLongId();
        Helper_Krco::sendOrderEmailStatusChanged($controller, "Order $orderId Status Report: $paidStatus", 'email/order_processed', $order);
    }

    static function _createAutoPointCoupon($controller, $member, $autoCouponConf)
    {
        $coupon = new Coupon();
        $coupon->setTitle('Bonus Coupon');
        $coupon->setDiscountPercentage(Helper_Structure::getArrayValue($autoCouponConf, 'discount_percentage'));
        $coupon->setDiscountAmount(Helper_Structure::getArrayValue($autoCouponConf, 'discount_amount'));
        $str = $member->getEmail() . $controller->now . 'AutoPointCoupon';
        $suffix = substr(md5($str), 0, 7);
        $coupon->setCouponCode('BONUS-' . $member->getEmail() . '-' . $suffix);
        $couponType = 'product';
        if (Helper_Structure::getArrayValue($autoCouponConf, 'coupon_type')) {
            $couponType = Helper_Structure::getArrayValue($autoCouponConf, 'coupon_type');
        }
        $coupon->setCouponType($couponType);
        $coupon->setIsAllProducts(TRUE);
        $coupon->setNumOfUsage(1);
        if ($expirySeconds = Helper_Structure::getArrayValue($autoCouponConf, 'expiry_seconds')) {
            $coupon->setExpiryDate(Helper_Date::formatSqlDatetime($controller->now + $expirySeconds));
        }
        return $coupon;
    }

    static function _sendAutoPointCouponEmail($controller, $member, $coupon)
    {
        $viewPageName = 'email/member_point_coupon';
        $couponArr = Helper_Objects::translateObjectToArr($controller, $coupon, 'couponToArr');
        $body = $controller->_getMemberEmailBodyFromViewPage($viewPageName, $member, array('coupon' => $couponArr));
        $smsBody = $controller->_getMemberEmailBodyFromViewPage('sms/member_point_coupon', $member, array('coupon' => $couponArr));
        $from = $controller->getNoReplyFrom();
        $to = $member->getEmail();
        $couponCode = $coupon->getCouponCode();
        $subject = "Bonus Coupon (Coupon Code: $couponCode)";
        $autoPointConfig = $controller->getKrcoConfigValue('cart', 'auto_point_coupon');
        if ($customSubject = Helper_Structure::getArrayValue($autoPointConfig, 'email_subject')) {
            $subject = $customSubject;
        }
        Helper_Mail::sendEmail($controller, $from, $to, NULL, NULL, $subject, $body);
        Helper_Mail::sendSms($controller, $member->getPhoneNumber(), $smsBody);
    }

    static function _handleAutoPointCoupon($controller, $member)
    {
        $autoCouponConf = $controller->getKrcoConfigValue('cart', 'auto_point_coupon');
        if (($autoCouponConf) && $member->getPoint() >= $autoCouponConf['point_required']) {
            $autoCoupon = self::_createAutoPointCoupon($controller, $member, $autoCouponConf);
            $added = $controller->dbCall('coupons', 'addCoupon', array($autoCoupon), TRUE);
            $usedPoint = $autoCouponConf['point_required'];
            $member->setPoint($member->getPoint() - $usedPoint);
            $controller->dbCall('members', 'updateMember', array($member), TRUE);
            self::_sendAutoPointCouponEmail($controller, $member, $autoCoupon);
        }
    }

    static function _setMemberPoint($controller, $order, $member, $reverse=FALSE)
    {
        if (!($order && $member)) {
            return FALSE;
        }
        $pointEarned = $order->getPointEarned();
        if($reverse) $pointEarned *= -1;
        $newPoint = $member->getPoint() + $pointEarned;
        $member->setPoint(max($newPoint, 0));
        $controller->dbCall('members', 'updateMember', array($member), TRUE);
        self::_handleAutoPointCoupon($controller, $member);
        return TRUE;
    }

    static function setInvoiceIdOfOrder($controller, $order, $orderDb, $configKey=NULL)
    {
        if ($controller->getObjKrcoConfig('with_auto_generate_invoice', $configKey) !== FALSE) {
            $invoiceFormat = $controller->_getIdFormat('invoice_id_format', $configKey);
            $newId = Helper_Cart::_getInvoiceId($controller, $orderDb, $invoiceFormat);
            $order->setInvoiceId($newId);
        }
    }

    static function _getItemsText($orderedProducts, $currency)
    {
        $itemsText = Helper_Cart::getSoldProductsText($orderedProducts, $currency);
        $text = '';
        if ($itemsText) {
            $text = "Items:\n" . $itemsText;
        }
        return $text;
    }

    static function _handleShippingRestriction($obj, $restr, $fieldName, $isEmpty=FALSE)
    {
        $newShip = NULL;
        $getMethod = 'get' . $fieldName;
        $oldShip = $obj->$getMethod();
        if (($restr !== TRUE) && !in_array($oldShip, $restr)) {
            $newShip = '';
            if (!$isEmpty && isset($restr[0])) {
                $newShip = $restr[0];
            }
        }
        if (isset($newShip)) {
            //$setMethod = 'set' . $fieldName;
            self::setAttrWithUpdate($obj, $fieldName, $newShip);
        }
    }

    static function _getShippingRestriction($restrictions, $obj)
    {
        if (!$obj || !is_array($restrictions)) {
            return NULL;
        }
        $country = $obj->getShippingCountry();
        if (!$country) {
            return NULL;
        }
        $city = $obj->getShippingCity();
        $restr = NULL;
        if (isset($restrictions[$country])) {
            $restr = $restrictions[$country];
        } else if (isset ($restrictions['_others'])) {
            $restr = $restrictions['_others'];
        }
        if (is_array($restr) && !empty($restrictions['applicable_cities'])) {
            $newRestr = array();
            foreach ($restr as $shipMethod) {
                $isCityApplicable = TRUE;
                if (!empty($restrictions['applicable_cities'][$shipMethod])) {
                    $isCityApplicable = FALSE;
                    foreach ($restrictions['applicable_cities'][$shipMethod] as $restrCity) {
                        if (strpos($city, $restrCity) !== FALSE) {
                            $isCityApplicable = TRUE;
                        }
                    }
                }
                if ($isCityApplicable) {
                    $newRestr[] = $shipMethod;
                }
            }
            $restr = $newRestr;
        }

        return $restr;
    }

    static function _handleCartShippingClash($controller, $obj)
    {
        $restrictions = self::getShippingRestrictions($controller);
        $restr = self::_getShippingRestriction($restrictions, $obj);
        if (isset($restr)) {
            self::_handleShippingRestriction($obj, $restr, 'ShippingDestination');
            self::_handleShippingRestriction($obj, $restr, 'BackorderShippingDestination', TRUE);
        }

        /* city restrictions */
        $country = $obj->getShippingCountry();
        $city = $obj->getShippingCity();
        $cities = Helper_Cities::getCitiesByCountry($controller, $country);
        if (!in_array($city, $cities)) {
            $newCity = NULL;
            /*
            if (isset($cities[0])) {
                $newCity = $cities[0];
            }
            */
            //self::setAttrWithUpdate($obj, 'ShippingCity', $newCity);
        }
    }

    static function setCartShippingInfos($controller, $obj)
    {
        if ($controller->getKrcoConfigValue('cart', 'with_set_default_cart_infos') !== FALSE) {
            $controller->_setCartShippingDestination($obj);
            //$controller->_setCartBackorderShippingDestination($obj);
            $controller->_setCartShippingCountry($obj);
            //$controller->_setCartShippingCity($obj);
        }
        self::_handleCartShippingClash($controller, $obj);
    }

    static function setAttrWithUpdate($obj, $fieldName, $value)
    {
        $setMethod = 'set' . $fieldName;
        $getMethod = 'get' . $fieldName;
        $oldValue = $obj->$getMethod();
        if ($oldValue != $value) {
            $obj->$setMethod($value);
            $obj->needsUpdate = TRUE;
        }
    }

    static function getPaymentNotifParamsOfOrder($order)
    {
        $params = array(
            'payment_method' => $order->getPaymentMethod(),
            'total_amount' => self::getTotalCostOfOrder($order),
            'order_id' => $order->getLongId(),
            'order_email' => $order->getRecipientEmail(),
        );
        return $params;
    }

    static function getDiscountCodes($discounts)
    {
        $codes = '';
        if ($discounts) {
            $codes = implode(', ', array_map(function($x) {return $x['code'];}, array_filter($discounts, function ($x) {return !isset($x['type']) || $x['type'] != 'special';})));
        }
        return $codes;
    }

    static function getActiveCurrency($currencies)
    {
        $activeCur = NULL;
        if ($currencies) {
            foreach ($currencies as $cur) {
                if (isset($cur['is_active']) && $cur['is_active']) {
                    $activeCur = $cur;
                }
            }
        }
        return $activeCur;
    }

    static function getShippingRestrictionsOfShippingMethods($controller, $methods)
    {
        $restrictions = array();
        foreach ($methods as $method) {
            $countries = $method->getApplicableCountries();
            $title = $method->getTitle();
            foreach ($countries as $country) {
                $restrictions[$country][] = $title;
            }
            if ($cities = $method->getApplicableCities()) {
                $restrictions['applicable_cities'][$title] = $cities;
            }
        }
        return $restrictions;
    }

    static function getShippingRestrictions($controller) {
        $restrictions = $controller->getKrcoConfigValue('cart', 'shipping_restrictions');
        if (!isset($restrictions) && $controller->getKrcoConfigVersion() >= 3) {
            $restrictions = array();
            $methods = $controller->getObjects('shipping_methods', 'getAllShippingMethods', array());
            if (isset($methods)) {
                $restrictions = self::getShippingRestrictionsOfShippingMethods($controller, $methods);
            }
            if ($restrictions && empty($restrictions['_others'])) {
                $restrictions['_others'] = array();
            }
        }
        return $restrictions;
    }

    static function generateBirthdayCouponForUser($controller, $birUser, $birthdayConfig)
    {
        $birCoupon = self::_createBirthdayCoupon($controller, $birUser, $birthdayConfig);
        $added = $controller->dbCall('coupons', 'addCoupon', array($birCoupon), TRUE);
        $discountPercentage = $birCoupon->getDiscountPercentage();
        if (isset($added)) {
            // send email
            $firstName = $birUser->getFirstName();
            $from = $controller->getNoReplyFrom();
            $to = $birUser->getEmail();
            $couponCode = $birCoupon->getCouponCode();
            $signature = $controller->_getEmailSignature();
            $body = <<<EOD
<p>
Happy Birthday, $firstName!
</p>
<p>
Quote '$couponCode' when shopping in our website, and you will enjoy a discount of $discountPercentage% for one purchase during this month.
</p>
$signature

EOD;
            Helper_Mail::sendEmailWithFooter($controller, $from, $to, NULL, NULL, 'Happy Birthday', $controller->_getHtmlEmailBody($body));
        }
    }

    static function _createBirthdayCoupon($controller, $member, $couponConf)
    {
        $coupon = new Coupon();
        $coupon->setCouponType('product');
        $coupon->setDiscountPercentage($couponConf['discount_percentage']);
        $coupon->setIsAllProducts(TRUE);
        $startDate = Helper_Date::formatSqlDatetime(strtotime(date('F Y', $controller->now)));
        $endDate = Helper_Date::formatSqlDatetime(strtotime(date('F Y', $controller->now) . ' +1 month'));
        $coupon->setStartDate($startDate);
        $coupon->setExpiryDate($endDate);
        $coupon->setNumOfUsage(1);
        $curYear = date('Y', $controller->now);
        $couponCode = 'BIRTHDAY-' . $member->getEmail() . "-$curYear";
        $coupon->setCouponCode($couponCode);
        $name = $member->getFirstName();
        $nyears = $curYear - date('Y', strtotime($member->getDateOfBirth()));
        $th = Helper_Math::getTh($nyears);
        $coupon->setTitle("$name's $nyears$th Birthday");
        return $coupon;
    }

    static function setDeliveryDate($controller, $cart)
    {
        $setter = $controller->getKrcoConfigValue('cart', 'set_delivery_date');
        if ($setter) {
            $setter($controller, $cart);
        }
    }

    static function cancelOrder($controller, $order, $setRemarks=NULL)
    {
        if (isset($setRemarks)) {
            $order->setRemarks($setRemarks);
        }

        $withCancelOrders = $controller->getKrcoConfigValue('cart', 'with_cancel_orders');
        if ($withCancelOrders === FALSE) {
            return ;
        }
        if ($order->getOrderStatus() == 'Cancelled') {
            return ;
        }
        $currentStatus = $order->getOrderStatus();

        $order->setOrderStatus('Cancelled');
        $controller->dbCall('orders', 'updateOrder', array($order), TRUE);

        $orderedProds = $order->getOrderedProducts();
        foreach ($orderedProds as $orderedProd) {
            $item = self::_orderedProdToItem($orderedProd);
            Helper_Cart::_subtractProductStock($item, $controller, TRUE);
        }
        $memberObj = $controller->getMemberOfOrder($order);
        $controller->_processMemberPlaceOrder($order, $memberObj, TRUE);

        //revert back point earned if any
        if($currentStatus == 'Processing') {
        	self::_setMemberPoint($controller, $order, $memberObj, TRUE);
        }

        $signature = $controller->_getEmailSignature();
        $from = $controller->getNoReplyFrom();
        $to = $controller->getOrdersEmailAddress();
        $orderId = $order->getLongId();
        $orderManageLine = Helper_Krco::getOrderManageLine($controller, $order);
        $body = <<<EOD
<p>
Dear Admin,
</p>
<p>
This is to notify that the Order with ID $orderId is expired.
</p>
<p>
$orderManageLine
</p>
$signature

EOD;
        Helper_Mail::sendEmail($controller, $from, $to, NULL, NULL, "Expired Order: $orderId", $controller->_getHtmlEmailBody($body));

        $name = $order->getRecipientName();
        $userBody = $controller->_getOrderExpiredEmailBodyToUser($order);
        $smsBody = $controller->getOrderExpiredSmsBodyToUser($order);
        $emails = $controller->_getOrderEmails($order);
        Helper_Mail::sendEmailWithFooter($controller, $from, $emails['to'], $emails['cc'], NULL, "Expired Order: $orderId", $userBody);
        Helper_Mail::sendSms($controller, $order->getRecipientPhoneNumber(), $smsBody);
    }

    static function _orderedProdToItem($orderedProd)
    {
        $item = new BasicCartItem();
        $item->setQuantity($orderedProd->getQuantity());
        $item->setProductFid($orderedProd->getProductFid());
        return $item;
    }

    static function orderStatusToShipmentStatus($status)
    {
        $shipments = array(
            'Pending' => 'No',
            'Verifying Payment' => 'No',
            'Processing' => 'No',
            'Shipped' => 'Yes',
            'Cancelled' => 'No',
        );
        if (isset($shipments[$status])) {
            return $shipments[$status];
        }
        return 'Unknown';
    }

    static function orderStatusToPaymentStatus($status)
    {
        $payments = array(
            'Pending' => 'No',
            'Verifying Payment' => 'Verifying',
            'Processing' => 'Yes',
            'Shipped' => 'Yes',
            'Cancelled' => 'No',
        );
        if (isset($payments[$status])) {
            return $payments[$status];
        }
        return 'Unknown';
    }

    static function getAmountSpentOfOrder($order)
    {
        return $order->getTotalCost() - $order->getShippingCost() - $order->getTax();
    }

    static function isMemberEligible($controller, $types)
    {
        $member = $controller->getMemberObj();
        if ($member && in_array($member->getMemberLevel(), $types)) {
            return TRUE;
        }
        return FALSE;
    }

    static function getPointsEarned($controller, $cart)
    {
        $pointRule = $controller->getPointRule();
        if ($pointRule) {
            if (is_callable($pointRule)) {
                $points = $pointRule($controller, $cart);
                return $points;
            }
            $isEligible = TRUE;
            if ($isEligibleFunc = Helper_Structure::getArrayValue($pointRule, 'is_earn_eligible')) {
                $isEligible = $isEligibleFunc($controller, $cart);
            }
            if ($eligibleMemberTypes = Helper_Structure::getArrayValue($pointRule, 'eligible_member_types')) {
                $isEligible = self::isMemberEligible($controller, Helper_String::commaStrToArr($eligibleMemberTypes));
            }
            if ($isEligible) {
                $spent = Helper_Cart::getAmountSpent($cart);
                if (!empty($pointRule['with_exclude_sale_items'])) {
                    $saleItems = array_filter($cart['items'], function ($x) {return !empty($x['is_sale']); });
                    $saleTotal = self::getItemTotal($saleItems);
                    $spent = max($spent - $saleTotal, 0);
                }
                return self::getPointsEarnedByAmountSpent($controller, $spent);
            }
        }
        return 0;
    }

    static function getPointsEarnedByAmountSpent($controller, $amount)
    {
        $pointRule = $controller->getPointRule();
        $pointPerDollar = Helper_Structure::getArrayValue($pointRule, 'point_per_dollar', 0);
        $points = (int)($pointPerDollar * $amount);
        return $points;
    }

    static function processCartInfo($controller, $transId)
    {
        if (isset($controller->ipnProcessor)) {
            $processor = $controller->ipnProcessor;
            return $processor($transId);
        }

        $cartCode = $transId;
        $memberId = NULL;
        $solds = array();
        $order = NULL;
        if (($p = strpos($transId, ';')) !== FALSE) {
        	$orderId = substr($transId, 0, $p);
        	$krcoObjKeyPl = substr($transId, $p+1);
        	$ordersDb = $controller->getKrcoConfigValue($krcoObjKeyPl, 'db_orders');
        	$order = $controller->getSingleObject($ordersDb, 'getOrderByLongId', array($orderId));
        	if ($order) {
        		$memberId = $order->getMemberId();
        		$solds = $order->getOrderedProducts();
        		$cartCode = $order->getShoppingCartFid();

        		$processed = Helper_Cart::processOrderPaid($controller, $order, $krcoObjKeyPl);
        		if (!$processed) {
        			return NULL;
        		}
        	}
        }
        $cartObj = self::getCartObjByCartCode($controller, $cartCode);

        if ($cartObj) {
        	if (!$memberId) $memberId = $cartObj->getMemberId();
        	if (!$solds) $solds = self::getSoldsOfCart($controller, $cartObj);
        }
        $info = array(
        		'cart_code' => $cartCode,
        		'member_id' => $memberId,
        		'solds' => $solds,
        		'order' => $order,
        );
        $controller->_processedCartInfo = $info;
        return $info;
    }

    static function getCartInfo($controller, $transId)
    {
    	$cartCode = $transId;
    	$memberId = NULL;
    	$solds = array();
    	$order = NULL;
    	if (($p = strpos($transId, ';')) !== FALSE) {
    		$orderId = substr($transId, 0, $p);
    		$krcoObjKeyPl = substr($transId, $p+1);
    		$ordersDb = $controller->getKrcoConfigValue($krcoObjKeyPl, 'db_orders');
    		$order = $controller->getSingleObject($ordersDb, 'getOrderByLongId', array($orderId));
    		if ($order) {
    			$memberId = $order->getMemberId();
    			$solds = $order->getOrderedProducts();
    			$cartCode = $order->getShoppingCartFid();
    		}
    	}
    	$cartObj = self::getCartObjByCartCode($controller, $cartCode);

    	if ($cartObj) {
    		if (!$memberId) $memberId = $cartObj->getMemberId();
    		if (!$solds) $solds = self::getSoldsOfCart($controller, $cartObj);
    	}
    	$info = array(
    			'cart_code' => $cartCode,
    			'member_id' => $memberId,
    			'solds' => $solds,
    			'order' => $order,
    	);
    	return $info;
    }

    static function getCartObjByCartCode($controller, $cartCode)
    {
        $cart = array('items' => array());
        $cartObj = $controller->getSingleObject('carts', 'getCartByCode', array($cartCode));
        return $cartObj;
    }

    static function getSoldsOfCart($controller, $cartObj)
    {
        $solds = array();
        $cart = Helper_Cart::cartToArr($cartObj, $controller);
        $solds = Helper_Cart::cartToSoldProducts(NULL, $cart, 'SoldProduct');
        return $solds;
    }

    static function shortenTransId($transId) {
    	$arr = explode(';', $transId);
    	if (count($arr) > 1 && strlen($arr[1])) {
    		$arr[1] = 'S'.strtoupper(substr($arr[1], 0, 1));
    		$arr = array($arr[1], $arr[0]);
    	}
    	return implode('', $arr);
    }

    static function decodeTransId($transId) {
    	if (strlen($transId) > 2) {
    		$arr = array(substr($transId, 0, 2), substr($transId, 2));
    		if ($arr[0] == 'SP') {
    			$arr[0] = 'products';
    		} else if ($arr[0] == 'SS') {
    			$arr[0] = 'subscribe';
    		} else {
    			return $transId;
    		}
    		return implode(';', array_reverse($arr));
    	}
    	return $transId;
    }

}
