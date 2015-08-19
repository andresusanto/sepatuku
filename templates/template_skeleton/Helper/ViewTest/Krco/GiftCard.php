<?php
class Helper_ViewTest_Krco_GiftCard
{
    static function getCase_StoreGiftCard_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Gift Card', 'gift_card', 'gift_card');
        return $testNormal;
    }
}
