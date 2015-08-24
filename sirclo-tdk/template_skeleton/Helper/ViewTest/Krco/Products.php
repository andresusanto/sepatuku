<?php
class Helper_ViewTest_Krco_Products
{
    static function getCase_StoreProducts_TestNormal($controller)
    {
        $recentlyViewed = NULL;
        if (isset($controller->krco_config['products']['recently_viewed'])) {
            $recentlyViewed = $controller->krco_config['products']['recently_viewed'];
        }
        $testNormal = $controller->_getViewTestDefaultData('Page Products', 'products', 'products');
        $testNormal += array(
            'products' => $controller->getTestProducts(17),
            'cart' => $controller->_getTestCartViewTest(),
            'categories' => $controller->getTestCategoriesForObjs(),
            'active_category' => $controller->getTestActiveCategory(),
            'paging' => $controller->getTestPaging(),
            'sorts' => $controller->getTestSorts(),
            //'informations' => $controller->getTestInformations(),
            'filter_fields' => $controller->getTestFilterFields(count($controller->getObjKrcoConfig('filter_fields'))),
            'recently_viewed_products' => $controller->getTestProducts($recentlyViewed),
        );
        if ($controller->getObjKrcoConfig('with_category_objects')) {
            $testNormal['category_products'] = $controller->getTestProducts(8);
        }
        if ($controller->getObjKrcoConfig('with_featured')) {
            $testNormal['featured_products'] = $controller->getTestProducts(11);
        }
        return $testNormal;
    }

    static function getCase_StoreProducts_TestNormalWithoutFilters($controller)
    {
        $recentlyViewed = NULL;
        if (isset($controller->krco_config['products']['recently_viewed'])) {
            $recentlyViewed = $controller->krco_config['products']['recently_viewed'];
        }
        $testNormal = $controller->_getViewTestDefaultData('Page Products', 'products', 'products');
        $testNormal += array(
            'products' => $controller->getTestProducts(17),
            'cart' => $controller->_getTestCartViewTest(),
            'categories' => $controller->getTestCategoriesForObjs(),
            'active_category' => $controller->getTestActiveCategory(),
            'paging' => $controller->getTestPaging(),
            'sorts' => $controller->getTestSorts(),
            //'informations' => $controller->getTestInformations(),
            // 'filter_fields' => $controller->getTestFilterFields(count($controller->getObjKrcoConfig('filter_fields'))),
            'recently_viewed_products' => $controller->getTestProducts($recentlyViewed),
        );
        if ($controller->getObjKrcoConfig('with_category_objects')) {
            $testNormal['category_products'] = $controller->getTestProducts(8);
        }
        if ($controller->getObjKrcoConfig('with_featured')) {
            $testNormal['featured_products'] = $controller->getTestProducts(11);
        }
        return $testNormal;
    }

    static function getCase_StoreProducts_TestCategoriesNoActive($controller)
    {
        $testCategoriesNoActive = self::getCase_StoreProducts_TestNormal($controller);
        $testCategoriesNoActive['categories'][1]['is_active'] = FALSE;
        $testCategoriesNoActive['paging'] = $controller->getTestPaging(1);
        $testCategoriesNoActive['categories'][1]['sub_nav'][1]['is_active'] = FALSE;
        unset($testCategoriesNoActive['active_category']);
        foreach ($testCategoriesNoActive['filter_fields'] as &$field) {
            unset($field['selected']);
        }
        return $testCategoriesNoActive;
    }

    static function getCase_StoreProducts_TestWithQuery($controller)
    {
        $testWithQuery = self::getCase_StoreProducts_TestNormal($controller);
        $testWithQuery['query'] = 'foo <strong>bar</strong>';
        $testWithQuery['paging']['link'] = 'http://paging?query=foo';
        $testWithQuery['paging']['current_page'] = 2;
        unset($testWithQuery['categories'][1]['sub_nav']);
        unset($testWithQuery['filter_fields']);
        $testWithQuery['active_category']['images'] = array();
        return $testWithQuery;
    }

    static function getCase_StoreProducts_TestNoProducts($controller)
    {
        $testNoProducts = self::getCase_StoreProducts_TestNormal($controller);
        $testNoProducts['products'] = array();
        $testNoProducts['categories'] = $controller->getTestCategoriesForObjs(FALSE);
        $testNoProducts['paging'] = $controller->getTestPaging(0);
        $testNoProducts['categories'][1]['sub_nav'][1]['description'] = '';
        return $testNoProducts;
    }

    static function getCase_StoreProducts_TestNoPaging($controller)
    {
        $testNoPaging = self::getCase_StoreProducts_TestNormal($controller);
        $testNoPaging['paging'] = NULL;
        $testNoPaging['categories'] = NULL;
        $testNoPaging['active_category']['description'] = '';
        return $testNoPaging;
    }

    static function getCase_StoreProducts_TestEmptyCartLongPaging($controller)
    {
        $testNoCart = self::getCase_StoreProducts_TestNormal($controller);
        $testNoCart['cart']['items'] = array();
        unset($testNoCart['recently_viewed_products']);
        $testNoCart['paging']['current_page'] = 1;
        unset($testNoCart['categories'][1]['sub_nav'][1]['is_active']);
        unset($testNoCart['categories'][1]['is_active']);
        $testNoCart['categories'][0]['is_active'] = TRUE;
        $testNoCart['categories'][0]['sub_nav'][0]['is_active'] = TRUE;
        $testNoCart['paging'] = $controller->getTestPaging(15, 30);
        return $testNoCart;
    }

    static function getCase_StoreProducts_TestAlternateView($controller)
    {
        $testAlternateView = self::getCase_StoreProducts_TestNormal($controller);
        $testAlternateView['view_type'] = 'alternate';
        return $testAlternateView;
    }

    static function getCase_StoreProducts_TestWithMember($controller)
    {
        $testProductsWithMember = self::getCase_StoreProducts_TestNormal($controller);
        $testProductsWithMember['member'] = $controller->getTestMember(7);
        unset($testProductsWithMember['categories'][1]['sub_nav'][1]['is_active']);
        return $testProductsWithMember;
    }

    static function getCase_StoreProducts_TestLongSubcategories($controller)
    {
        $testLongSubcategories = self::getCase_StoreProducts_TestNormal($controller);
        foreach ($testLongSubcategories['categories'] as &$cat) {
            $cat['sub_nav'] = array_map(function ($x) {return array('title' => "Long Subcategory $x", 'link' => "http://longsub$x");}, range(1, 20));
        }
        return $testLongSubcategories;
    }

    static function getCase_StoreProductDetails_TestOddId($controller)
    {
        $recentlyViewed = NULL;
        if (isset($controller->krco_config['products']['recently_viewed'])) {
            $recentlyViewed = $controller->krco_config['products']['recently_viewed'];
        }
        $testOddId = $controller->_getViewTestDefaultData('Page Product Details', 'products', 'products');
        if ($customOptionTest = ($controller->getKrcoConfigValue('cart', 'edit_item_test_options'))) {
            $testOddId['object_options'] = $customOptionTest;
        }
        if ($controller->getObjKrcoConfig('with_category_objects')) {
            $testOddId['category_products'] = $controller->getTestProducts(8);
        }
        $testOddId += array(
            'product' => $controller->getTestProduct(3),
            'related_products' => $controller->getTestProducts($controller->getKrcoConfigValue('products', 'related')),
            'recently_viewed_products' => $controller->getTestProducts($recentlyViewed),
            'cart' => $controller->_getTestCartViewTest(),
        );
        if ($controller->getObjKrcoConfig('with_categories_on_details')) {
            $testOddId['categories'] = $controller->getTestCategoriesForObjs();
        }
        if ($controller->getObjKrcoConfig('with_prev_next')) {
            $testOddId['prev_next_products'] = $controller->getTestProducts(2);
        }
        if ($controller->getObjKrcoConfig('with_sister_objects')) {
            $tempProducts = $controller->getTestProducts(4);
            $sisterProducts = array();
            foreach ($tempProducts as $prod) {
                $sisterProducts['Red'] = $tempProducts[0];
                $sisterProducts['Green'] = $tempProducts[1];
                $sisterProducts['Blue'] = $tempProducts[2];
                $sisterProducts['Yellow'] = $tempProducts[3];
            }
            $testOddId['sister_products'] = $sisterProducts;
        }
        return $testOddId;
    }

    static function getCase_StoreProductDetails_TestEvenId($controller)
    {
        $testEvenId = self::getCase_StoreProductDetails_TestOddId($controller);
        $testEvenId['product'] = $controller->getTestProduct(10);
        unset($testEvenId['recently_viewed_products']);
        if ($controller->getObjKrcoConfig('with_prev_next')) {
            $testEvenId['prev_next_products'] = $controller->getTestProducts(1);
        }
        return $testEvenId;
    }

    static function getCase_StoreProductDetails_TestOnlySizeOptions($controller)
    {
        $testOnlySizeOptions = self::getCase_StoreProductDetails_TestOddId($controller);
        $testOnlySizeOptions['product'] = $controller->getTestProduct(7);
        $testOnlySizeOptions['product']['options']['color'] = array();
        return $testOnlySizeOptions;
    }

    static function getCase_StoreProductDetails_TestNoRelatedProducts($controller)
    {
        $testNoRelatedProducts = self::getCase_StoreProductDetails_TestOddId($controller);
        $testNoRelatedProducts['related_products'] = array();
        $testNoRelatedProducts['product'] = $controller->getTestProduct(5);
        return $testNoRelatedProducts;
    }

    static function getCase_StoreProductDetails_TestWithMember($controller)
    {
        $testWithMember = self::getCase_StoreProductDetails_TestOddId($controller);
        $testWithMember['member'] = $controller->getTestMember(7);
        return $testWithMember;
    }

    static function getCase_StoreProductDetails_TestNoOptions($controller)
    {
        $testNoOptions = self::getCase_StoreProductDetails_TestOddId($controller);
        $testNoOptions['product']['options']['color'] = array();
        $testNoOptions['product']['options']['size'] = array();
        if (!empty($testNoOptions['product']['general_options'])) {
            foreach ($testNoOptions['product']['general_options'] as $key => &$value) {
                $value['options'] = array();
            }
        }
        return $testNoOptions;
    }

    static function getCase_StoreProductDetails_TestNoPrice($controller)
    {
        $testNoPrice = self::getCase_StoreProductDetails_TestOddId($controller);
        $testNoPrice['product'] = $controller->getTestProduct(16);
        return $testNoPrice;
    }

    static function getCase_StoreProductCategories_TestNormal($controller)
    {
        $testProductCategories = $controller->_getViewTestDefaultData('Page Product Categories', 'products', 'products');
        $testProductCategories += array(
            'categories' => $controller->getTestCategoriesForObjs(),
        );
        if ($controller->getKrcoConfigValue('products', 'with_objects_in_categories')) {
            $testProductCategories['products'] = $controller->getTestProducts(11);
        }
        return $testProductCategories;
    }

    static function getCase_StoreProductOrder_TestOddId($controller)
    {
        return self::getCase_StoreProductDetails_TestOddId($controller);
    }

    static function getCase_StoreProductOrder_TestEvenId($controller)
    {
        return self::getCase_StoreProductDetails_TestEvenId($controller);
    }

    static function getCase_StoreProductOrder_TestWithMember($controller)
    {
        return self::getCase_StoreProductDetails_TestWithMember($controller);
    }
    
    static function getCase_StoreWishProducts_TestNormal($controller)
    {
        $testWishNormal = $controller->_getViewTestDefaultData('Page Wish List', 'products', 'products');
        $testWishNormal += array(
            'wish_products' => $controller->getTestProducts(17),
            'member' => $controller->getTestMember(7),
        );
        return $testWishNormal;
    }

    static function getCase_StoreWishProducts_TestEmpty($controller)
    {
        $testWishEmpty = self::getCase_StoreWishProducts_TestNormal($controller);
        $testWishEmpty['wish_products'] = array();
        return $testWishEmpty;
    }

    static function getCase_StoreCompareProducts_TestNormal($controller)
    {
        $testCompareNormal = $controller->_getViewTestDefaultData('Page Compare Products', 'products', 'products');
        $testCompareNormal += array(
            'compare_products' => $controller->getTestProducts(3),
        );
        return $testCompareNormal;
    }

    static function getCase_StoreCompareProducts_TestMany($controller)
    {
        $testCompareNormal = $controller->_getViewTestDefaultData('Page Compare Products', 'products', 'products');
        $testCompareNormal += array(
            'compare_products' => $controller->getTestProducts(7),
        );
        return $testCompareNormal;
    }

    static function getCase_StoreCompareProducts_TestEmpty($controller)
    {
        $testCompareEmpty = self::getCase_StoreCompareProducts_TestNormal($controller);
        $testCompareEmpty['compare_products'] = array();
        return $testCompareEmpty;
    }
}
