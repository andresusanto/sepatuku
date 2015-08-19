<?php
 /**    Plugin File */
 /**
  * Function to get image source
  *
  * This function get image source based on the parameter and return the result
  * 
  *
  * @param $params variable that contains the image that want to be generated the source
  * @param $template to associate source
  *
  * @return string the source of the image
  */
function smarty_function_sirclo_get_imgsrc($params, $template)
{
    $s = '';
    if (isset($params['str'])) {
        $s = Helper_String::getImgSrc($params['str']);
    }
    return $s;
}
