<?php
 /**    Plugin File */
 /**
  * Function to generate account edit password form
  *
  * This function render edit password form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated form
  *
  * @return string HTMLcode of the account password edit form <form>...</form>
  */
function smarty_function_sirclo_render_account_edit_password($params, $template)
{
    $html = Helper_Renderer::sircloRenderAccountEditPassword($params);
    return $html;
}
