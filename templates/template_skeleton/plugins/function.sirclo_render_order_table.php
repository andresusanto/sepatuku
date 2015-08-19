<?php
 /**    Plugin File */
 /**
  * Function to generate order table
  *
  * This function generate order table
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the order table <table>...</table>
  */
function smarty_function_sirclo_render_order_table($params, $template)
{
    $html = '';
    if (isset($params['order'])) {
        $colors = array(
            'color1' => Helper_Structure::getArrayValue($params, 'color1'),
            'color2' => Helper_Structure::getArrayValue($params, 'color2'),
        );
        $html = Helper_Renderer::renderOrderTable($params['order'], $colors);
    }
    return $html;
}
