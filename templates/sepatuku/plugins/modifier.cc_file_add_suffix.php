<?php
function smarty_modifier_cc_file_add_suffix($string, $suff='') {
    return Helper_File::addSuffix($string, $suff);
} 
