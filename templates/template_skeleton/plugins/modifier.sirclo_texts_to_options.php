<?php
 /**    Modifier File */
 /**
  * Function to change texts into options
  *
  * This function get string from text and make it into options
  * 
  *
  * @param $texts
  *
  * @return options that resulted from text
  */
function smarty_modifier_sirclo_texts_to_options($texts) {
    return Helper_Structure::getOptionsFromTexts($texts);
} 
