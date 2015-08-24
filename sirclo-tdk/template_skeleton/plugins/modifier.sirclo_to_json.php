<?php
 /**    Modifier File */
 /**
  * Function to change array instance into json
  *
  * This function encode array into json
  * 
  *
  * @param $texts
  *
  * @return json encoded array
  */

function smarty_modifier_sirclo_to_json($arr) {
    return json_encode($arr);
} 
