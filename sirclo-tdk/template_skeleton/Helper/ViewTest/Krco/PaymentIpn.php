<?php
class Helper_ViewTest_Krco_PaymentIpn
{
    static function getCase_StorePaymentRedirect_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Payment Redirect', '', '');
        $testNormal += array(
            'redirect_info' => array(
                'link' => 'http://localhost/debug.php',
                'params' => array(
                    'foo' => 'bar',
                    'alice' => 'bob',
                ),
            ),
        );
        return $testNormal;
    }
}
