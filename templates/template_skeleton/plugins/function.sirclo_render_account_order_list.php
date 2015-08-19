<?php
 /**    Plugin File */
 /**
  * Function to generate list of recent order
  *
  * This function take the parameter as option of the generated result
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the order list in table
  */
function smarty_function_sirclo_render_account_order_list($params, $template)
{
    $html = Helper_Renderer::sircloRenderOrderList($params);
    return $html;
}
