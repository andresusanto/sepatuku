<?php
class Helper_ViewTest_Krco_Home
{
    static function getCase_StoreHome_TestWithFeaturedBox($controller)
    {
        $testWithFeaturedBox = self::getCase_StoreHome_TestNormal($controller);
        $testWithFeaturedBox['featured_box'] = $controller->getTestFeaturedBox();
        return $testWithFeaturedBox;
    }

    static function getCase_StoreHome_TestIsFirstVisit($controller)
    {
        $testIsFirstVisit = self::getCase_StoreHome_TestNormal($controller);
        $testIsFirstVisit['is_first_visit'] = TRUE;
        $testIsFirstVisit['member'] = $controller->getTestMember(7);
        return $testIsFirstVisit;
    }

    static function getCase_StoreHome_TestWithMessage($controller)
    {
        $testWithMessage = self::getCase_StoreHome_TestNormal($controller);
        $testWithMessage['message'] = 'Thank you for contacting us.';
        $testWithMessage['message_type'] = 'success';
        return $testWithMessage;
    }

    static function getCase_StoreHome_TestSingleSlide($controller)
    {
        $testSingleSlide = self::getCase_StoreHome_TestNormal($controller);
        if (isset($testSingleSlide['slides'])) $testSingleSlide['slides'] = array($testSingleSlide['slides'][0]);
        return $testSingleSlide;
    }

    static function getCase_StoreHome_TestWithMember($controller)
    {
        $testWithMember = self::getCase_StoreHome_TestNormal($controller);
        $testWithMember['member'] = $controller->getTestMember(7);
        return $testWithMember;
    }

    static function _getNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Home', 'home', 'home');
        unset($testNormal['breadcrumb']);
        $testNormal += array(
            'meta' => array(
                'description' => 'This is meta description.',
                'keywords' => 'Keyword 1, Keyword 2, Keyword 3',
            ),
            'language_options' => array(
                array(
                    'title' => 'English',
                    'link' => 'http://en.wikipedia.org',
                ),
                array(
                    'title' => 'Bahasa',
                    'link' => 'http://id.wikipedia.org',
                    'is_active' => TRUE,
                ),
                array(
                    'title' => '中文',
                    'link' => 'http://zh.wikipedia.org',
                    'is_active' => FALSE,
                ),
            ),
            'now_timestamp' => time(),
        );
        return $testNormal;
    }

    static function getCase_StoreHome_TestNormal($controller)
    {
        $testNormal = self::_getNormal($controller);
        $fetch_objects = $controller->_getTestFetchObjects($controller->krco_config['home']);
        $testNormal += $fetch_objects;
        return $testNormal;
    }

    static function getCase_StoreHome_TestNoFetchObjects($controller)
    {
        $testNoFetchObjects = self::_getNormal($controller);
        $fetch_objects = $controller->_getTestFetchObjects($controller->krco_config['home']);
        foreach ($fetch_objects as &$fetch_object) {
            $fetch_object = NULL;
        }
        $testNoFetchObjects += $fetch_objects;
        return $testNoFetchObjects;
    }
}
