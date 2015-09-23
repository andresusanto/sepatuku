<?php

class Helper_Array {
  /**
   * Restructure $_FILES such that the offsets are not in the middle of the fields
   * @param  array $files original $_FILES
   * @return restructured $_FILES
   */
  static function getFileFilteredFilesArr($files) {
    $arr = $files;
    
    foreach ($files as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $attrKey => $attrVal) {
          if (is_array($attrVal)) {
            foreach ($attrVal as $i => $x) {
              $newKey = $key;
              
              if ($i) {
                $newKey .= "-$i";
              }
              
              $arr[$newKey][$attrKey] = $x;
            }
          }
        }
      }
    }
    return $arr;
  }  
  /**
   * Build a tree from flat list
   * @param  array $list          List that will be treeified
   * @param  string $idAttr       Attribute name of $list's element to get element's id
   * @param  string $parentAttr   Attribute name of $list's element to get element's parent id
   * @param  string $childrenAttr Name of children attribute to assign
   * @return array                Treeified $list
   */
  static function treeify($list, $idAttr = 'id', $parentAttr = 'parent_id', $childrenAttr = 'children') {
    $treeList = array();
    $lookup = array();

    for ($i=0;$i<count($list);$i++) {
      $obj = &$list[$i];
      $lookup[$obj[$idAttr]] = &$obj;
      $obj[$childrenAttr] = array();
    }

    for ($i=0;$i<count($list);$i++) {
      $obj = &$list[$i];
      $parent_id = $obj[$parentAttr];
      if (isset($parent_id) and ($parent_id != 0)) {
        $lookup[$parent_id][$childrenAttr][] = &$obj;
      } else {
        $treeList[] = &$obj;
      }
    }

    return $treeList;
  }

}
