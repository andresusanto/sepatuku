<?php
class Helper_ViewTest_Krco_Contact
{
    static function getCase_StoreContact_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Contact', 'contact', 'contact');
        if (isset($controller->krco_config['contact_info'])) {
            $testNormal['location'] = $controller->getTestLocation(1);
        }
        return $testNormal;
    }

    static function getCase_StoreContact_TestWithMessageSuccess($controller)
    {
        $testWithMessageSuccess = self::getCase_StoreContact_TestNormal($controller);
        $testWithMessageSuccess['message'] = "Thank you for contacting us.\nWe will respond within 48 hours.";
        $testWithMessageSuccess['message_type'] = 'success';
        return $testWithMessageSuccess;
    }

    static function getCase_StoreContact_TestWithMessageError($controller)
    {
        $testWithMessageError = self::getCase_StoreContact_TestNormal($controller);
        $testWithMessageError['message'] = 'Sorry, an error occurred.';
        $testWithMessageError['message_type'] = 'error';
        return $testWithMessageError;
    }
}
