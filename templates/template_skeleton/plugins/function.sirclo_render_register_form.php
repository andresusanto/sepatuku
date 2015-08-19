<?php
 /**    Plugin File */
 /**
  * Function to generate register form
  *
  * This function generate register form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the register form <form>...</form>
  */
function smarty_function_sirclo_render_register_form($params, $template) {
    $_html = Helper_Renderer::sircloRenderRegisterForm($params);
    return $_html;
}
