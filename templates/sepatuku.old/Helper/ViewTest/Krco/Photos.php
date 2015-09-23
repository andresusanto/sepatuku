<?php
class Helper_ViewTest_Krco_Photos
{
    static function getCase_StorePhotos_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Photos', 'photos', 'photos');
        $testNormal += array(
            'photos' => $controller->getTestPhotos(),
            'paging' => $controller->getTestPaging(),
            'categories' => $controller->getTestCategories(),
            'active_category' => $controller->getTestActiveCategory(),
        );
        return $testNormal;
    }

    static function getCase_StorePhotoDetails_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Photos', 'photos', 'photos');
        $testNormal += array(
            'photo' => $controller->getTestPhoto(3),
        );
        if ($controller->getObjKrcoConfig('with_prev_next')) {
            $testNormal['prev_next_photos'] = $controller->getTestPhotos(2);
        }
        return $testNormal;
    }

    static function getCase_StorePhotoCategories_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Photo Categories', 'photos', 'photos');
        $testNormal += array(
            'categories' => $controller->getTestCategories(),
            'paging' => $controller->getTestPaging(),
        );
        return $testNormal;
    }

    static function getCase_StorePhotoCategories_TestNoPaging($controller)
    {
        $testNoPaging = self::getCase_StorePhotoCategories_TestNormal($controller);
        unset($testNoPaging['paging']);
        return $testNoPaging;
    }
}
