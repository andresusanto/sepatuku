<?php
class Helper_Sirclo
{
    public static $packages = array(
        'a' => array(
            'limit_sku' => 100,
            'limit_storage' => 1024,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => FALSE,
            'with_doku' => FALSE,
        	'with_dokumyshortcart' => FALSE,
            'with_enets' => FALSE,
            'with_facebook' => FALSE,
            'with_loyalty_point' => FALSE,
            'with_birthday_coupons' => FALSE,
            'with_account_invite' => FALSE,
            'with_multi_currencies' => FALSE,
            'with_bulk_upload' => FALSE,
            'with_abandoned_cart_saver' => FALSE,
            'with_multi_member_types' => FALSE,
            'with_gift_card' => FALSE,
            'with_order_report' => FALSE,
            'with_coupons' => FALSE,
            'with_email_footer_setting' => FALSE,
            'with_acl' => FALSE,
        ),
        'b' => array(
            'limit_sku' => 1000,
            'limit_storage' => 1024,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => FALSE,
            'with_doku' => FALSE,
        	'with_dokumyshortcart' => FALSE,
            'with_enets' => FALSE,
            'with_facebook' => TRUE,
            'with_loyalty_point' => FALSE,
            'with_birthday_coupons' => FALSE,
            'with_account_invite' => FALSE,
            'with_multi_currencies' => FALSE,
            'with_bulk_upload' => FALSE,
            'with_abandoned_cart_saver' => FALSE,
            'with_multi_member_types' => FALSE,
            'with_gift_card' => FALSE,
            'with_order_report' => TRUE,
            'with_coupons' => TRUE,
            'with_email_footer_setting' => TRUE,
            'with_acl' => FALSE,
        ),
        'b-veritrans' => array(
            'limit_sku' => 1000,
            'limit_storage' => 1024,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => TRUE,
            'with_doku' => FALSE,
            'with_dokumyshortcart' => FALSE,
            'with_enets' => FALSE,
            'with_facebook' => TRUE,
            'with_loyalty_point' => FALSE,
            'with_birthday_coupons' => FALSE,
            'with_account_invite' => FALSE,
            'with_multi_currencies' => FALSE,
            'with_bulk_upload' => FALSE,
            'with_abandoned_cart_saver' => FALSE,
            'with_multi_member_types' => FALSE,
            'with_gift_card' => FALSE,
            'with_order_report' => TRUE,
            'with_coupons' => TRUE,
            'with_email_footer_setting' => TRUE,
            'with_acl' => FALSE,
        ),
        'c' => array(
            'limit_sku' => 5000,
            'limit_storage' => 5120,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => TRUE,
            'with_doku' => TRUE,
        	'with_dokumyshortcart' => TRUE,
            'with_enets' => TRUE,
            'with_facebook' => TRUE,
            'with_loyalty_point' => TRUE,
            'with_birthday_coupons' => TRUE,
            'with_account_invite' => TRUE,
            'with_multi_currencies' => TRUE,
            'with_bulk_upload' => TRUE,
            'with_abandoned_cart_saver' => TRUE,
            'with_multi_member_types' => FALSE,
            'with_gift_card' => FALSE,
            'with_order_report' => TRUE,
            'with_coupons' => TRUE,
            'with_email_footer_setting' => TRUE,
            'with_acl' => FALSE,
        ),
        'd' => array(
            'limit_sku' => NULL,
            'limit_storage' => NULL,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => TRUE,
            'with_doku' => TRUE,
        	'with_dokumyshortcart' => TRUE,
            'with_enets' => TRUE,
            'with_facebook' => TRUE,
            'with_loyalty_point' => TRUE,
            'with_birthday_coupons' => TRUE,
            'with_account_invite' => TRUE,
            'with_multi_currencies' => TRUE,
            'with_bulk_upload' => TRUE,
            'with_abandoned_cart_saver' => TRUE,
            'with_multi_member_types' => TRUE,
            'with_gift_card' => TRUE,
            'with_order_report' => TRUE,
            'with_coupons' => TRUE,
            'with_advanced_pricing' => TRUE,
            'with_email_footer_setting' => TRUE,
            'with_acl' => TRUE,
        ),
        'staging' => array(
            'limit_sku' => NULL,
            'limit_storage' => NULL,
            'with_check_orders' => TRUE,
            'with_paypal' => TRUE,
            'with_veritrans' => TRUE,
            'with_doku' => TRUE,
            'with_loyalty_point' => TRUE,
            'with_birthday_coupons' => TRUE,
            'with_account_invite' => TRUE,
            'with_multi_currencies' => TRUE,
            'with_bulk_upload' => TRUE,
            'with_abandoned_cart_saver' => TRUE,
            'with_multi_member_types' => TRUE,
            'with_gift_card' => TRUE,
            'with_order_report' => TRUE,
            'with_coupons' => TRUE,
            'with_advanced_pricing' => TRUE,
            'with_acl' => TRUE,
        ),
        'test' => array(
            'limit_sku' => '2',
            'limit_storage' => 1,
        ),
    );

    static function getPackageConfigValue($controller, $key)
    {
        $packageId = Helper_Structure::getArrayValue($controller->deployment_config, 'sirclo_package_id');
        return self::getPackageConfigValueById($packageId, $key);
    }

    static function getPackageConfigValueById($id, $key)
    {
        $package = Helper_Structure::getArrayValue(self::$packages, $id);
        return Helper_Structure::getArrayValue($package, $key);
    }

    static function generateGiftCardProduct($controller, $fid)
    {
        $obj = new EcommerceProduct();
        $obj->setFriendlyId($fid);
        $obj->setIsIgnoreStock(TRUE);
        $obj->setTitle('Gift Card');
        $obj->setIsFreeShipping(TRUE);
        $imageUrl = $controller->getDepConfigValue('giftcard_image_url');
        if ($imageUrl) {
            $obj->setImages(array($controller->composeLink($imageUrl)));
        }
        $obj->__detailsLink = '/gift_card';
        $obj->__calculatePrice = function ($controller, $cartItem, $member, $product) {
            return Helper_Krco::calculatePriceGiftCard($controller, $cartItem, $member, $product);
        };
        return $obj;
    }
}
