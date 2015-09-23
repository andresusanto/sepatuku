<?php
 /**    Plugin File */
 /**
  * Function to generate account info
  *
  * This function render account info
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the account info as table <table>...</table>
  */
function smarty_function_sirclo_render_account_info($params, $template)
{
    $html = Helper_Renderer::sircloRenderAccountInfo($params);
    return $html;
}
