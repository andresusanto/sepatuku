<?php
class Helper_ViewTest_Krco_Testimonials
{
    static function getCase_StoreTestimonials_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Testimonials', 'testimonials', 'testimonials');
        $testNormal += array(
            'testimonials' => $controller->getTestTestimonials(),
            'paging' => $controller->getTestPaging(),
            'categories' => $controller->getTestCategories(),
        );
        return $testNormal;
    }

    static function getCase_StoreTestimonialDetails_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Testimonial Details', 'testimonials', 'testimonials');
        $testNormal += array(
            'testimonial' => $controller->getTestTestimonial(7),
            'related_testimonials' => $controller->getTestObjs('Testimonial', 2, 4),
        );
        return $testNormal;
    }

    static function getCase_StoreTestimonialSubmit_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Testimonial Submit', 'testimonials', 'testimonials');
        return $testNormal;
    }
}
