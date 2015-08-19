<?php
 /**    Plugin File */
 /**
  * Function to generate place order form
  *
  * This function generate place order form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the place order form <form>...</form>
  */
function smarty_function_sirclo_render_place_order_form($params, $template)
{
    $html = Helper_Renderer::sircloRenderPlaceOrderForm($params);
    return $html;
}
