<?php
class Helper_ViewTest_Krco_Articles
{
    static function getCase_StoreStatic_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Static', 'about', 'articles');
        $testNormal += array(
            'static_data' => $controller->getTestStaticData(),
            'slides' => $controller->getTestSlides(7, array('fname' => 'static')),
        );
        $crossRelatedData = $controller->_getViewTestCrossRelatedData('articles');
        if ($crossRelatedData) {
            $testNormal += $crossRelatedData;
        }
        return $testNormal;
    }

    static function getCase_StoreStatic_TestSingleNav($controller)
    {
        $testSingleNav = self::getCase_StoreStatic_TestNormal($controller);
        $testSingleNav['static_data']['nav'] = array($testSingleNav['static_data']['nav'][1]);
        $testSingleNav['static_data']['images'] = array();
        unset($testSingleNav['slides']);
        return $testSingleNav;
    }

    static function getCase_StoreStatic_TestSingleSlide($controller)
    {
        $testSingleSlide = self::getCase_StoreStatic_TestNormal($controller);
        $testSingleSlide['slides'] = array($testSingleSlide['slides'][0]);
        $testSingleSlide['static_data']['nav'][3]['is_active'] = TRUE;
        $testSingleSlide['static_data']['images'] = array(
            $testSingleSlide['static_data']['images'][0],
        );
        return $testSingleSlide;
    }

    static function getCase_StoreStatic_TestShort($controller)
    {
        $testShort = self::getCase_StoreStatic_TestNormal($controller);
        $testShort['static_data']['content'] = "<p>This is a very short content.</p>";
        return $testShort;
    }

    static function getCase_StoreStatic_TestShortSingleNav($controller)
    {
        $testShortSingleNav = self::getCase_StoreStatic_TestShort($controller);
        $testShortSingleNav['static_data']['nav'] = array($testShortSingleNav['static_data']['nav'][1]);
        return $testShortSingleNav;
    }
}
