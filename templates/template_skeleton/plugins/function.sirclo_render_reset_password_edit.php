<?php
 /**    Plugin File */
 /**
  * Function to generate reset password edit form
  *
  * This function generate reset password edit form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the reset password edit form <form>...</form>
  */
function smarty_function_sirclo_render_reset_password_edit($params, $template)
{
    $html = Helper_Renderer::sircloRenderResetPasswordEdit($params);
    return $html;
}
