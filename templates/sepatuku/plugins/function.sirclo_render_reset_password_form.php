<?php
 /**    Plugin File */
 /**
  * Function to generate reset password form
  *
  * This function generate reset password form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the reset password form <form>...</form>
  */
function smarty_function_sirclo_render_reset_password_form($params, $template)
{
    $html = Helper_Renderer::sircloRenderResetPassword($params);
    return $html;
}
