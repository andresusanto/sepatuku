<?php
 /**    Plugin File */
 /**
  * Function to generate css that used by template that adopted SIRCLO platform
  *
  * This function is used to generate css that required by template that requires SIRCLO platform
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string List of css link 
  */
function smarty_function_sirclo_render_css($params, $template)
{
    $_html = '
        <link rel="stylesheet" href="//cdn.sirclo.com/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="//cdn.sirclo.com/sirclo.css" type="text/css">';
        return $_html;
    return $_html;
}
