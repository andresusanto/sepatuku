<?php
class Helper_ViewTest_Krco_PressReleases
{
    static function getCase_StorePressReleases_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Press Release', 'press_releases', 'press_releases');
        $testNormal += array(
            'press_releases' => $controller->getTestPressReleases(),
            'paging' => $controller->getTestPaging(),
            'categories' => $controller->getTestCategories(),
        );
        return $testNormal;
    }

    static function getCase_StorePressReleases_TestEmpty($controller)
    {
        $testEmpty = self::getCase_StorePressReleases_TestNormal($controller);
        $testEmpty['press_releases'] = array();
        return $testEmpty;
    }

    static function getCase_StorePressReleases_TestNoPaging($controller)
    {
        $testNoPaging = self::getCase_StorePressReleases_TestNormal($controller);
        $testNoPaging['paging'] = NULL;
        return $testNoPaging;
    }

    static function getCase_StorePressReleaseDetails_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Press Release Details', 'press_releases', 'press_releases');
        $testNormal += array(
            'press_release' => $controller->getTestPressRelease(7),
            'related_press_releases' => $controller->getTestPressReleases(3),
            'prev_next_press_releases' => $controller->getTestPressReleases(2),
        );
        if ($controller->getObjKrcoConfig('with_category_objects')) {
            $testNormal['category_press_releases'] = $controller->getTestPressReleases(8);
        }
        if ($controller->getObjKrcoConfig('with_categories_on_details')) {
            $testNormal['categories'] = $controller->getTestCategories();
        }
        return $testNormal;
    }

    static function getCase_StorePressReleaseDetails_TestNoImage($controller)
    {
        $testNoImage = self::getCase_StorePressReleaseDetails_TestNormal($controller);
        $testNoImage['press_release'] = $controller->getTestPressRelease(8);
        $testNoImage['prev_next_press_releases'][0] = NULL;
        return $testNoImage;
    }
}
