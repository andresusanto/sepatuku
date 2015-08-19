<?php
class Helper_ViewTest_Krco_Cart
{
    static function getCase_StoreCart_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Cart', NULL, 'cart');
        $shippingMethods = array('Normal', 'Registered');
        if ($configMethods = $controller->getKrcoConfigValue('cart', 'shipping_methods')) {
            $shippingMethods = $configMethods;
        }
        $testNormal += array(
            'cart' => $controller->_getTestCartViewTest(),
            'shipping_methods' => $shippingMethods,
            'shipping_cities' => array('Ambon', 'Jakarta', 'Surabaya', 'Medan'),
            'paypal' => $controller->getTestPaypal(),
            'continue_url' => 'http://continue',
            'categories' => $controller->getTestCategories(),
            'member' => NULL,
        );
        return $testNormal;
    }

    static function getCase_StoreCart_TestWithMember($controller)
    {
        $testWithMember = self::getCase_StoreCart_TestNormal($controller);
        $testWithMember['member'] = $controller->getTestMember(7);
        $testWithMember['message'] = 'Item has been added to cart.';
        $testWithMember['message_type'] = 'success';
        return $testWithMember;
    }

    static function getCase_StoreCart_TestWithShipping($controller)
    {
        $testWithShipping = self::getCase_StoreCart_TestNormal($controller);
        $testWithShipping['cart']['shipping'] = 10;
        $testWithShipping['cart']['shipping_discount'] = -1;
        $testWithShipping['cart']['discounts'] = $controller->_getTestDiscounts();
        $testWithShipping['cart']['tax'] = 1.555;
        $testWithShipping['cart']['is_checkout_enabled'] = FALSE;
        $testWithShipping['cart']['checkout_disabled_reason'] = 'You must purchase at least 10 items.';
        return $testWithShipping;
    }

    static function getCase_StoreCart_TestEmpty($controller)
    {
        $testEmpty = self::getCase_StoreCart_TestNormal($controller);
        $testEmpty['cart']['items'] = array();
        $testEmpty['cart']['total_items'] = 0;
        return $testEmpty;
    }

    static function getCase_StoreCartJson_TestNormal($controller)
    {
        return self::getCase_StoreCart_TestWithShipping($controller);
    }

    static function getCase_StoreCartJson_TestSuccess($controller)
    {
        $testJsonSuccess = self::getCase_StoreCart_TestNormal($controller);
        $testJsonSuccess['cart']['total_items'] = 4;
        $testJsonSuccess['cart']['grand_total'] = 2000.5;
        unset($testJsonSuccess['cart']['items'][2]);
        $testJsonSuccess['message'] = 'Item has been added successfully.';
        $testJsonSuccess['message_type'] = 'success';
        return $testJsonSuccess;
    }

    static function getCase_StoreCartJson_TestError($controller)
    {
        $testJsonError = self::getCase_StoreCart_TestNormal($controller);
        unset($testJsonError['cart']['items'][2]);
        $testJsonError['cart']['total_items'] = 0;
        $testJsonError['message'] = 'An error occurred.';
        $testJsonError['message_type'] = 'error';
        return $testJsonError;
    }

    static function getCase_StoreCartJson_TestEditCart($controller)
    {
        $testEditCart = self::getCase_StoreCartJson_TestSuccess($controller);
        $testEditCart['cart']['shipping'] = rand(10, 100);
        $shipping_value = Helper_Structure::getArrayValue($_POST, 'shipping_value');
        if ($shipping_value != 'ID') {
            $testEditCart['shipping_cities'] = array();
        }
        $testEditCart['cart']['shipping_city'] = $shipping_value;
        return $testEditCart;
    }

    static function getCase_StoreCartEditItem_TestItemNormal($controller)
    {
        $testItemNormal = $controller->_getViewTestDefaultData('Page Cart', 'products', 'cart');
        $testItemNormal += array(
            'product' => $controller->getTestProduct(7),
            'cart' => $controller->getTestCart(),
            'cart_item' => array(
                'quantity' => '20',
                'options' => array(
                    'text' => 'Hello World',
                    'font' => 'Font 2',
                    'font-color' => 'Color 2',
                    'box-design' => 'Design 2',
                    'sticker-design' => 'Sticker Design 2',
                    'add-ons' => 'Add-On 2 (price), Add-On 3, Add-On 4 (price)',
                ),
            ),
        );
        if ($customOptionTest = ($controller->getKrcoConfigValue('cart', 'edit_item_test_options'))) {
            $testItemNormal['cart_item']['options'] = $customOptionTest;
        }
        return $testItemNormal;
    }

    static function getCase_StoreCartEditItem_TestItemEmpty($controller)
    {
        $testItemEmpty = self::getCase_StoreCartEditItem_TestItemNormal($controller);
        $testItemEmpty['cart_item']['options'] = array();
        return $testItemEmpty;
    }

    static function getCase_StoreCartPlaceOrder_TestNormal($controller)
    {
        $shippingMethods = array('Normal', 'Registered');
        if ($configMethods = $controller->getKrcoConfigValue('cart', 'shipping_methods')) {
            $shippingMethods = $configMethods;
        }
        $testNormal = $controller->_getViewTestDefaultData('Page Cart Place Order', NULL, 'cart');
        unset($testNormal['breadcrumb']);
        $testNormal += array(
            'context_links' => array(
                'account_login' => 'http://context/login',
                'account_register' => 'http://context/register',
            ),
            'cart' => $controller->_getTestCartViewTest(),
            'shipping_methods' => $shippingMethods,
            'shipping_cities' => array('Ambon', 'Jakarta', 'Surabaya', 'Medan'),
            'paypal' => $controller->getTestPaypal(),
            'payment_methods' => $controller->getTestPaymentMethods(),
            'continue_url' => 'http://continue',
            'member' => NULL,
        );
        return $testNormal;
    }

    static function getCase_StoreCartPlaceOrder_TestWithShipping($controller)
    {
        $testWithShipping = self::getCase_StoreCartPlaceOrder_TestNormal($controller);
        $testWithShipping['cart']['shipping'] = 10;
        $testWithShipping['cart']['shipping_discount'] = -1;
        $testWithShipping['cart']['discounts'] = $controller->_getTestDiscounts();
        $testWithShipping['cart']['tax'] = 1.555;
        $testWithShipping['cart']['is_checkout_enabled'] = FALSE;
        $testWithShipping['cart']['checkout_disabled_reason'] = 'You must purchase at least 10 items.';
        return $testWithShipping;
    }

    static function getCase_StoreCartPlaceOrder_TestWithMember($controller)
    {
        $testWithMember = self::getCase_StoreCartPlaceOrder_TestNormal($controller);
        $testWithMember['member'] = $controller->getTestMember(7);
        $testWithMember['account_addresses'] = $controller->getTestObjs('Address', 1, 3);
        return $testWithMember;
    }

    static function getCase_StoreCartPlaceOrder_TestWithMemberEven($controller)
    {
        $testWithMemberEven = self::getCase_StoreCartPlaceOrder_TestNormal($controller);
        $testWithMemberEven['member'] = $controller->getTestMember(8);
        return $testWithMemberEven;
    }

    static function getCase_StoreCartPlaceOrder_TestCartEmpty($controller)
    {
        $testCartEmpty = self::getCase_StoreCartPlaceOrder_TestNormal($controller);
        $testCartEmpty['cart']['items'] = array();
        return $testCartEmpty;
    }

    static function getCase_StoreCartPlaceOrder_TestCartZeroAmount($controller)
    {
        $testCartZeroAmount = self::getCase_StoreCartPlaceOrder_TestNormal($controller);
        $testCartZeroAmount['cart']['grand_total'] = 0;
        return $testCartZeroAmount;
    }

    static function getCase_StoreCartOrderPlaced_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Cart Order Placed', NULL, 'cart');
        unset($testNormal['breadcrumb']);
        $testNormal += array(
            'order_message' => "This is a message.\nThis is the second line.",
            'order' => $controller->getTestOrder(7),
        );
        return $testNormal;
    }

    static function getCase_StoreCartOrderPlaced_TestPaypal($controller)
    {
        $testPaypal = self::getCase_StoreCartOrderPlaced_TestNormal($controller);
        $testPaypal['order']['payment_method'] = 'paypal';
        return $testPaypal;
    }

    static function getCase_StoreCartOrderPlaced_TestBank($controller)
    {
        $testBank = self::getCase_StoreCartOrderPlaced_TestNormal($controller);
        $testBank['order']['payment_method'] = 'bank-transfer';
        return $testBank;
    }

    static function getCase_StoreCartOrderPlaced_TestCod($controller)
    {
        $testCod = self::getCase_StoreCartOrderPlaced_TestNormal($controller);
        $testCod['order']['payment_method'] = 'cod';
        return $testCod;
    }

    static function getCase_StoreCartOrderPlaced_TestNoOrder($controller)
    {
        $testNoOrder = self::getCase_StoreCartOrderPlaced_TestNormal($controller);
        unset($testNoOrder['order']);
        return $testNoOrder;
    }
}
