<?php
function smarty_modifier_cc_filter_array_by_keys($arr, $keys) {
    return Helper_Structure::filterArrayByKeys($arr, $keys);
} 
