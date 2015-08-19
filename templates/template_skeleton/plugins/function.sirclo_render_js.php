<?php
 /**    Plugin File */
 /**
  * Function to generate js that used by template that adopted SIRCLO platform
  *
  * This function is used to generate basic js for the sake of the template functionality. 
  * The list of js that listed here doesn't need to be redeclared by template maker. 
  * <ul>  
  * <li> jquery.min.js - jQuery</li>  
  * <li> jquery.validate.min.js - jQuery validation</li>  
  * <li> jquery-ui.min.js - jQuery UI feature</li>  
  * <li> ajax.js - Ajax</li>  
  * <li> area_autocomplete.js - Auto complete feature</li>  
  * </ul>  
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string List of js declarations 
  */
function smarty_function_sirclo_render_js($params, $template)
{
    $_html = '';

    $_html .= '
    <script type="text/javascript" src="//cdn.sirclo.com/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.sirclo.com/jquery.validate.min.js"></script>
    <script type="text/javascript" src="//cdn.sirclo.com/additional-methods.min.js"></script>
    <script type="text/javascript" src="//cdn.sirclo.com/jquery-ui.min.js"></script>';

    $_html .= '
    <script type="text/javascript" src="//cdn.sirclo.com/sirclo.js"></script>
    <script type="text/javascript" src="//cdn.sirclo.com/ajax.js"></script>
    <script type="text/javascript" src="//cdn.sirclo.com/area_autocomplete.js"></script>';

    return $_html;
    // $html = "";//Helper_Renderer::sircloRenderJs($params);
    // return $html;
}
