<?php
class Helper_ViewTest_Krco_GeneralOrders
{
    static function getCase_StoreOrderInvoice_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Order Invoice', 'orders', 'orders');
        $testNormal += array(
            'order' => $controller->getTestOrder(7),
        );
        return $testNormal;
    }

    static function getCase_StoreOrderSubmit_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Order Submit', 'orders', 'orders');
        $testNormal['payment_methods'] = $controller->getTestPaymentMethods();
        return $testNormal;
    }

    static function getCase_StoreOrderSubmit_TestWithMember($controller)
    {
        $testWithMember = self::getCase_StoreOrderSubmit_TestNormal($controller);
        $testWithMember['member'] = $controller->getTestMember(7);
        return $testWithMember;
    }

    static function getCase_StoreOrderPrepayment_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Order Payment', 'orders', 'orders');
        $testNormal['breadcrumb'] = NULL;
        $testNormal += array(
            'order' => $controller->getTestOrder(7),
            'payment_link' => 'http://www.google.com',
        );
        return $testNormal;
    }
}
