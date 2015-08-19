<?php
class Helper_ProductPricing
{
    static function isConditionSatisfied($controller, $cartItem, $member, $product, $condKey, $condValue)
    {
        if ($condKey == 'member_type') {
            if ($member && $condValue == $member->getMemberLevel()) {
                return TRUE;
            }
        }
        if ($condKey == 'min_quantity') {
            if ($condValue <= $cartItem->getQuantity()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    static function isConditionsSatisfied($controller, $cartItem, $member, $product, $conditions)
    {
        $isSatisfied = TRUE;
        foreach ($conditions as $condKey => $condValue) {
            $newIsSatisfied = self::isConditionSatisfied($controller, $cartItem, $member, $product, $condKey, $condValue);
            $isSatisfied = $isSatisfied && $newIsSatisfied;
        }
        return $isSatisfied;
    }

    static function applyPricing($controller, $cartItem, $member, $product, $pricing)
    {
        if (!empty($pricing['discount_absolute'])) {
            return $product->getPrice() - $pricing['discount_absolute'];
        }
        if (!empty($pricing['discount_percentage'])) {
            return $product->getPrice() * (100-$pricing['discount_percentage'])/100;
        }
        if (!empty($pricing['set_price'])) {
            return $pricing['set_price'];
        }
        return NULL;
    }

    static function calculateAdvancedProductPriceWithRule($controller, $cartItem, $member, $product, $rule)
    {
        $conditions = Helper_Structure::getArrayValue($rule, 'conditions');
        if (!$conditions) {
            return NULL;
        }
        if (self::isConditionsSatisfied($controller, $cartItem, $member, $product, $conditions)) {
            $price = self::applyPricing($controller, $cartItem, $member, $product, Helper_Structure::getArrayValue($rule, 'pricing'));
            return $price;
        }
    }

    static function calculateAdvancedProductPrice($controller, $cartItem, $member, $product, $rules)
    {
        /*
        if ($product->getSalePrice()) {
            return $product->getSalePrice();
        }
        */
        $price = NULL;//$product->getPrice();
        foreach ($rules as $rule) {
            $newPrice = self::calculateAdvancedProductPriceWithRule($controller, $cartItem, $member, $product, $rule);
            if (isset($newPrice)) {
                return $newPrice;
            }
        }
        return $price;
    }
}
