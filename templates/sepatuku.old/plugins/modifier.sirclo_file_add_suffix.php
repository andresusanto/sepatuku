<?php
 /**    Modifier File */
 /**
  * Function to add suffix to file name
  *
  * This function adds suffix to file name
  * 
  *
  * @param $fullname full name of the string
  * @param $suff the suffix that want to be added
  *
  * @return string of fullname with the added suffix
  */
function smarty_modifier_sirclo_file_add_suffix($fullname, $suff='') {
    $pi = pathinfo($fullname);
    $extension = '';
    if (isset($pi['extension'])) {
        $extension = '.' . $pi['extension'];
    }
    $dir_pref = '';
    if (isset($pi['dirname']) && ($pi['dirname'] != '.')) {
        $dir_pref = $pi['dirname'] . '/';
    }
    return $dir_pref . $pi['filename'] . $suff . $extension;
} 
