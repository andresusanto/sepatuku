<?php
function smarty_function_cc_render_cart_table($params, $template)
{
    $renderer = Renderer_Cart::getInstanceFromSmartyParams($params);
    $renderParams = array();
    if (isset($params['with_image'])) $renderParams['with_image'] = $params['with_image'];
    if (isset($params['with_edit_item'])) $renderParams['with_edit_item'] = $params['with_edit_item'];
    if (isset($params['with_break_options'])) $renderParams['with_break_options'] = $params['with_break_options'];
    $tableHtml = $renderer->renderCartTable($params['cart'], $renderParams);
    return $tableHtml;
}
