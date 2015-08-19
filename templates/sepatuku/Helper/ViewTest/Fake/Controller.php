<?php
class Helper_ViewTest_Fake_Controller
{
    static function getCase_ParentSuiteOne_TestThree($controller)
    {
        return $controller->someAttribute;
    }

    static function getCase_ParentSuiteSeven_TestThree($controller)
    {
        return 'data seven';
    }
}
