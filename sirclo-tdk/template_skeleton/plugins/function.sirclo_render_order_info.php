<?php
 /**    Plugin File */
 /**
  * Function to generate order info
  *
  * This function generate order info
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return HTML code of the order info
  */
function smarty_function_sirclo_render_order_info($params, $template)
{
    $html = '';
    if (isset($params['order'])) {
        $colors = array(
            'color1' => Helper_Structure::getArrayValue($params, 'color1'),
            'color2' => Helper_Structure::getArrayValue($params, 'color2'),
        );
        $html = Helper_Renderer::renderOrderInfo($params['order'], $colors);
    }
    return $html;
}
