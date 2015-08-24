<?php
class Helper_ViewTest_Krco_PaymentNotif
{
    static function getCase_StorePaymentNotif_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Payment Notif', 'payment_notif', 'payment_notif');
        $bank = <<<EOD
<ul>
<li>ABC 001-001-001</li>
<li>XYZ 002-002-002</li>
</ul>

EOD;
        $testNormal['payment_instruction'] = Helper_ViewTest::getLoremIpsumP(3) . $bank;
        $testNormal['bank_accounts'] = $controller->getTestObjs('BankAccount', 1, 3);
        return $testNormal;
    }

    static function getCase_StorePaymentNotif_TestWithAmount($controller)
    {
        $testWithAmount = self::getCase_StorePaymentNotif_TestNormal($controller);
        $testWithAmount += array(
            'total_amount_payable' => 3000.50,
            'order_id' => 'ORDER0001',
            'order_email' => 'email@domain.com',
        );
        return $testWithAmount;
    }

    static function getCase_StorePaymentNotif_TestWithMessage($controller)
    {
        $testWithMessage = self::getCase_StorePaymentNotif_TestNormal($controller);
        $testWithMessage['message'] = 'Thank you for contacting us.';
        $testWithMessage['message_type'] = 'success';
        return $testWithMessage;
    }

    static function getCase_StorePaymentNotif_TestNoBankAccounts($controller)
    {
        $testNoBankAccounts = self::getCase_StorePaymentNotif_TestNormal($controller);
        $testNoBankAccounts['bank_accounts'] = array();
        return $testNoBankAccounts;
    }
}
