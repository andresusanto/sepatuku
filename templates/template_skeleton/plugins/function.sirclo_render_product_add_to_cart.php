<?php
 /**    Plugin File */
 /**
  * Function to generate product add to cart form
  *
  * This function generate product add to cart form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the product add to cart form <form>...</form>
  */
function smarty_function_sirclo_render_product_add_to_cart($params, $template)
{
    $_html = Helper_Renderer::sircloRenderProductAddToCart($params);
    return $_html;
}
