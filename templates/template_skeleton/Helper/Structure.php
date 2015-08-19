<?php
class Helper_Structure
{
    static function _getSubNavFromArticleLabel($label, $controller)
    {
        $subNav = array();
        $articles = $controller->dbCall('articles', 'getArticlesByLabel', array($label), TRUE);
        if ($articles) {
            $subNav = Helper_Krco::buildNavFromArticles($controller, $label, $articles);
            /*
            foreach ($articles as $art) {
                $fid = $art->getFriendlyId();
                $key = "$label/$fid";
                $subNav[$key] = array(
                    'title' => $art->getTitle($controller->lang),
                    'link' => self::composeArticleLink($controller, $label, $fid)
                );
            }
            */
        }
        return $subNav;
    }

    static function composeArticleLink($controller, $label, $fid)
    {
        $path = '';
        if ($fid) {
            $path = "/$fid";
        }
        $prefix = "/$label";
        if ($pref = $controller->getKrcoConfigValue('links', $label)) {
            $prefix = $pref;
        }
        $url = $controller->composeLink($prefix . $path, NULL, FALSE);
        return $url;
    }

    static function filterArrayByKeys($arr, $keys)
    {
        $ret = array();
        foreach ($keys as $key) {
            if (isset($arr[$key])) {
                $ret[$key] = $arr[$key];
            }
        }
        return $ret;
    }

    static function _getSubNavFromDb($key_pl, $controller, $type)
    {
        $subNav = array();
        if (method_exists($controller, 'getSubNav')) {
            $subNav = $controller->getSubNav($type, $key_pl);
        }
        return $subNav;
    }

    static function _generateSubNav(&$item, $controller)
    {
        $subNav = NULL;
        if (isset($item['sub_nav_generator']) && isset($controller->articles)) {
            $subNav = self::_getSubNavFromArticleLabel($item['sub_nav_generator'], $controller);
        }
        if (isset($item['sub_nav_db'])) {
            $type = '';
            if (isset($item['sub_nav_db_type'])) {
                $type = $item['sub_nav_db_type'];
            }
            $subNav = self::_getSubNavFromDb($item['sub_nav_db'], $controller, $type);
        }
        if (isset($subNav)) {
            if (count($subNav)>0) {
                $item['sub_nav'] = $subNav;
            }
            if (!$subNav) {
                $item['is_empty'] = TRUE;
            }
        }
    }

    static function _getTestSubNav($id, $title)
    {
        if (is_array($id) && isset($id['category'])) {
            $id = $id['category'];
        }
        if (is_array($id) && isset($id['fid'])) {
            $id = $id['fid'];
        }
        return array(
            "$id-1" => array(
                'title' => "$title 1: Subtitle 1 Very Long Title",
                'link' => "http://$id-1",
            ),
            "$id-2" => array(
                'title' => "$title 2: Subtitle 2",
                'link' => "http://$id-2",
                'sub_nav' => array(
                    "$id-2a" => array(
                        'title' => "$title 2a: Subtitle 2a",
                        'link' => "http://$id-2a",
                    ),
                    "$id-2b" => array(
                        'title' => "$title 2b: Subtitle 2b",
                        'link' => "http://$id-2b",
                        'sub_nav' => array(
                            "$id-2b1" => array(
                                'title' => "$title 2b1: Subtitle 2b1",
                                'link' => "http://$id-2b1",
                            ),
                            "$id-2b2" => array(
                                'title' => "$title 2b2: Subtitle 2b2",
                                'link' => "http://$id-2b2",
                            ),
                        ),
                    ),
                    "$id-2c" => array(
                        'title' => "$title 2c: Subtitle 2c",
                        'link' => "http://$id-2c",
                    ),
                ),
            ),
            "$id-3" => array(
                'title' => "$title 3: Subtitle 3",
                'link' => "http://$id-3",
                'is_active' => TRUE,
            ),
            "$id-4" => array(
                'title' => "$title 4",
                'link' => "http://$id-3",
            ),
        );
    }

    static function _generateSubNavFake(&$item, $controller)
    {
        if (isset($item['sub_nav_generator']) && isset($controller->articles)) {
            $item['sub_nav'] = self::_getTestSubNav($item['sub_nav_generator'], $item['title']);
            unset($item['sub_nav_generator']);
        }
        if (isset($item['sub_nav_db_type'])) {
            $item['sub_nav'] = self::_getTestSubNav($item['sub_nav_db_type'], $item['title']);
            unset($item['sub_nav_db_type']);
            unset($item['sub_nav_db']);
        }
    }

    static function isNavParentOf($parent, $child)
    {
        if (!$parent) {
            return FALSE;
        }
        $isParentOf = (strpos($child, $parent) === 0) && ((substr($child, strlen($parent), 3) == '_-_') || (substr($child, strlen($parent), 1) == '/'));
        return $isParentOf;
    }

    static function _isKeyActiveSingle($key, $active)
    {
        $isActive = ($key && $active) && (($key == $active) || self::isNavParentOf($key, $active));
        return $isActive;
    }

    static function isKeyActive($key, $active)
    {
        $actives = $active;
        if (!is_array($active)) {
            $actives = array($active);
        }
        $isActive = FALSE;
        foreach ($actives as $act) {
            $isActive = $isActive || self::_isKeyActiveSingle($key, $act);
        }
        return $isActive;
    }

    static function getNav($nav, $active, $controller=NULL, $isFake=FALSE)
    {
        if (!is_array($nav)) {
            return array();
        }
        foreach ($nav as $key => &$item) {
            if (self::isKeyActive($key, $active)) {
                $item['is_active'] = TRUE;
            }
            if (!$isFake) {
                self::_generateSubNav($item, $controller);
            } else {
                self::_generateSubNavFake($item, $controller);
            }
            if (isset($item['sub_nav'])) {
                $tempNav = self::getNav($item['sub_nav'], $active, $controller, $isFake);
                $item['sub_nav'] = $tempNav;
                $isActive = FALSE;
                foreach ($tempNav as $tempItem) {
                    if (isset($tempItem['is_active']) && $tempItem['is_active'] && !$isFake) {
                        $isActive = TRUE;
                    }
                }
                if ($isActive) {
                    $item['is_active'] = TRUE;
                }
            }
        }
        return $nav;
    }

    static function getOptionsFromTexts($texts)
    {
        $arr = array();
        foreach ($texts as $t) {
            $arr[] = array(
                'title' => $t,
                'value' => $t,
            );
        }
        return $arr;
    }

    static function getNumberOptionsFromTexts($texts, $prefix='')
    {
        $arr = array();
        foreach ($texts as $t) {
            $arr[] = array(
                'title' => $prefix . number_format($t),
                'value' => $t,
            );
        }
        return $arr;
    }

    static function getLastNElements($arr, $n)
    {
        $arr = array_slice($arr, max(0, count($arr)-$n), $n);
        return $arr;
    }

    static function mapObjToArr($obj, $map)
    {
        $arr = array();
        if (is_array($map)) {
            foreach ($map as $key => $value) {
                $getMethod = 'get' . $value;
                $arr[$key] = $obj->$getMethod();
            }
        }
        return $arr;
    }
    
    static function applyAttributes($obj, $attrs)
    {
        foreach ($attrs as $key => $attr) {
            $obj->$key = $attr;
        }
    }

    static function arrayOrderingFollow($arr, $base)
    {
        $temp = array();
        foreach ($arr as $arrItem) {
            $temp[$arrItem] = TRUE;
        }
        $follow = array();
        foreach ($base as $baseItem) {
            if (isset($temp[$baseItem])) {
                $follow[] = $baseItem;
            }
        }
        foreach ($arr as $arrItem) {
            if (!in_array($arrItem, $follow)) {
                $follow[] = $arrItem;
            }
        }
        return $follow;
    }

    static function getArrayValue($arr, $key, $default=NULL)
    {
        if (isset($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }

    static function getObjCall($obj, $method, $default=NULL)
    {
        if (is_callable(array($obj, $method))) {
            return $obj->$method();
        }
        return $default;
    }

    static function arrayInsert($arr, $afterKey, $newKey, $newVal)
    {
        $ret = array();
        if ($afterKey == -1) {
            $ret[$newKey] = $newVal;
        }
        foreach ($arr as $key => $val) {
            $ret[$key] = $val;
            if ($afterKey === $key) {
                $ret[$newKey] = $newVal;
            }
        }
        $ret[$newKey] = $newVal;
        return $ret;
    }

    static function arrayKeyReverse($arr)
    {
        $reversed = array();
        foreach ($arr as $key => $val) {
            $reversed[$val] = $key;
        }
        return $reversed;
    }

    static function getFirstArrayElement($arr)
    {
        $el = NULL;
        foreach ($arr as $item) {
            $el = $item;
            break;
        }
        return $el;
    }

    static function _getAttrFromObj($attr, $obj1, $obj2=NULL)
    {
        $getter = function ($x) use ($attr) {
            $getMethod = 'get' . $attr;
            $val = NULL;
            if (is_callable(array($x, $getMethod))) {
                $val = $x->$getMethod();
            }
            return $val;
        };
        return self::_getAttrWithGetter($getter, $obj1, $obj2);
    }

    static function _getAttrWithGetter($getter, $obj1, $obj2)
    {
        $val = $getter($obj1);
        if (!$val && $obj2) {
            $val = $getter($obj2);
        }
        return $val;
    }

    static function _getAttrFromArr($attr, $arr1, $arr2)
    {
        $getter = function ($x) use ($attr) {
            return $x[$attr];
        };
        return Helper_Structure::_getAttrWithGetter($getter, $arr1, $arr2);
    }
    
    static function arrayMergeIfExists($arr1, $arr2)
    {
        $arr = $arr1;
        foreach ($arr2 as $item) {
            if (!in_array($item, $arr)) {
                $arr[] = $item;
            }
        }
        return $arr;
    }

    static function arrayMergeIfKeyEmpty($arr1, $arr2)
    {
        $merged = $arr1;
        foreach ($arr2 as $key => $value) {
            if (!Helper_Structure::getArrayValue($merged, $key)) {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

    static function arrayMergeRecursive($arr1, $arr2)
    {
        $arr = $arr1;
        foreach ($arr2 as $key => $val) {
            if (isset($arr[$key]) && is_array($arr[$key]) && is_array($val)) {
                $arr[$key] = self::arrayMergeRecursive($arr[$key], $val);
            } else {
                $arr[$key] = $val;
            }
        }
        return $arr;
    }

    static function insertToArrayUnique($arr, $x)
    {
        if (!in_array($x, $arr)) {
            $arr[] = $x;
        }
        return $arr;
    }

    static function removeFromArray($arr, $x)
    {
        $newArr = array();
        foreach ($arr as $key => $val) {
            if ($val != $x) {
                $newArr[] = $val;
            }
        }
        return $newArr;
    }
}
