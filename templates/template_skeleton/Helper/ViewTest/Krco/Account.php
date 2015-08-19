<?php
class Helper_ViewTest_Krco_Account
{
    static function getCase_StoreAccount_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Account', 'account', 'account');
        $testNormal += array(
            'member' => $controller->getTestMember(6),
        );
        return $testNormal;
    }

    static function getCase_StoreAccount_TestWithImages($controller)
    {
        $testWithImages = self::getCase_StoreAccount_TestNormal($controller);
        $testWithImages['member'] = $controller->getTestMember(7);
        $testWithImages['message'] = 'Thank you for contacting us.';
        $testWithImages['message_type'] = 'success';
        return $testWithImages;
    }

    static function getCase_StoreAccount_TestEmpty($controller)
    {
        $testEmpty = self::getCase_StoreAccount_TestNormal($controller);
        $testEmpty['member'] = $controller->getTestMember(0);
        return $testEmpty;
    }

    static function getCase_StoreAccountEdit_TestNormal($controller)
    {
        return self::getCase_StoreAccount_TestNormal($controller);
    }

    static function getCase_StoreAccountEdit_TestWithImages($controller)
    {
        return self::getCase_StoreAccount_TestWithImages($controller);
    }

    static function getCase_StoreAccountEdit_TestEmpty($controller)
    {
        return self::getCase_StoreAccount_TestEmpty($controller);
    }

    static function getCase_StoreAccountEditPassword_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Account Edit Password', 'account', 'account');
        $testNormal += array(
            'member' => $controller->getTestMember(7),
        );
        return $testNormal;
    }

    static function getCase_StoreAccountEditPassword_TestWithMessageSuccess($controller)
    {
        $testWithMessageSuccess = self::getCase_StoreAccountEditPassword_TestNormal($controller);
        $testWithMessageSuccess['message'] = 'Your changes have been saved.';
        $testWithMessageSuccess['message_type'] = 'success';
        return $testWithMessageSuccess;
    }

    static function getCase_StoreAccountAddressesManage_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Account Addresses', 'account', 'account');
        $testNormal += array(
            'account_addresses' => $controller->getTestObjs('Address', 1, 3),
        );
        return $testNormal;
    }

    static function getCase_StoreAccountAddressEdit_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Account Address Edit', 'account', 'account');
        $testNormal += array(
            'account_address' => $controller->getTestAddress(7),
        );
        return $testNormal;
    }

    static function getCase_StoreAccountAddressEdit_TestModeNew($controller)
    {
        $testModeNew = self::getCase_StoreAccountAddressEdit_TestNormal($controller);
        $testModeNew['details_mode'] = 'new';
        return $testModeNew;
    }

    static function getCase_StoreAccountInvite_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Account Invite', 'account', 'account');
        return $testNormal;
    }

    static function getCase_StoreLogin_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Login', 'account', 'account');
        $testNormal += array(
            'context_links' => array(
                'account_login' => 'http://context/login',
                'account_register' => 'http://context/register',
            ),
        );
        return $testNormal;
    }

    static function getCase_StoreLogin_TestWithGuestCheckout($controller)
    {
        $testWithGuestCheckout = self::getCase_StoreLogin_TestNormal($controller);
        $testWithGuestCheckout['guest_checkout_link'] = 'http://guestcheckout';
        return $testWithGuestCheckout;
    }

    static function getCase_StoreLogin_TestWithMessage($controller)
    {
        $testWithMessage = self::getCase_StoreLogin_TestNormal($controller);
        $testWithMessage['message'] = 'Thank you for signing up.'; $testWithMessage['message_type'] = 'success';
        return $testWithMessage;
    }

    static function getCase_StoreRegister_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Register', 'account', 'account');
        return $testNormal;
    }

    static function getCase_StoreOrderList_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Order List', 'account', 'account');
        $testNormal += array(
            'orders' => $controller->getTestObjs('Order', 1, 4),
            'orders_amount_accumulated' => 7000,
            'member' => $controller->getTestMember(7),
            'paging' => $controller->getTestPaging(),
        );
        return $testNormal;
    }

    static function getCase_StoreOrderList_TestNoPaging($controller)
    {
        $testNoPaging = self::getCase_StoreOrderList_TestNormal($controller);
        unset($testNoPaging['paging']);
        return $testNoPaging;
    }

    static function getCase_StoreOrderList_TestEmpty($controller)
    {
        $testEmpty = self::getCase_StoreOrderList_TestNormal($controller);
        $testEmpty['orders'] = array();
        return $testEmpty;
    }

    static function getCase_StoreResetPassword_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Reset Password', 'account', 'account');
        return $testNormal;
    }

    static function getCase_StoreResetPasswordEdit_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Reset Password Edit', 'account', 'account');
        return $testNormal;
    }
}
