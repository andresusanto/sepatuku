<?php
 /**    Plugin File */
 /**
  * Function to generate variant details to json
  *
  * This function generate variant details to json
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string json of the detailed variants
  */
function smarty_modifier_sirclo_detailed_variants_to_json($product, $currency) {
    $arr = $product['detailed_variants'];
    foreach ($arr as $key => $value) {
      $arr[$key] = $currency . " " . number_format($value['price'], 2);
    }
    return json_encode($arr);
} 
