<?php
 /**    Modifier File */
 /**
  * Function to filter array by keys
  *
  * This function filter array by keys
  * 
  *
  * @param $arr the array that want to be filtered
  * @param $keys the key filter
  *
  * @return array the filtered result
  */
function smarty_modifier_sirclo_filter_array_by_keys($arr, $keys) {
    return Helper_Structure::filterArrayByKeys($arr, $keys);
} 
