<?php
 /**    Plugin File */
 /**
  * Function to generate account edit form
  *
  * This function render edit account form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated form
  *
  * @return string HTML code of the account edit form <form>...</form>
  */
function smarty_function_sirclo_render_account_edit_form($params, $template)
{
    $html = Helper_Renderer::sircloRenderAccountEditInfo($params);
    return $html;
}
