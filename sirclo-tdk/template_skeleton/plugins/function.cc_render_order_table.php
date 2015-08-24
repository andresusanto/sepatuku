<?php
function smarty_function_cc_render_order_table($params, $template)
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
