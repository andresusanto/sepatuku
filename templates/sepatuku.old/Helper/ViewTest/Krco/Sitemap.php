<?php
class Helper_ViewTest_Krco_Sitemap
{
    static function getCase_StoreSitemap_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Sitemap', 'sitemap', 'sitemap');
        $testNormal += array(
            'sitemap' => $controller->getTestSitemap(),
        );
        return $testNormal;
    }
}
