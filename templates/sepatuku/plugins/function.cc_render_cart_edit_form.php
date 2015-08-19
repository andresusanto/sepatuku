<?php
function smarty_function_cc_render_cart_edit_form($params, $template)
{
    $renderer = Renderer_Cart::getInstanceFromSmartyParams($params);
    $renderParams = array();
    if (isset($params['label'])) $renderParams['label'] = $params['label'];
    if (isset($params['options'])) $renderParams['options'] = $params['options'];
    if (isset($params['shipping_country_options'])) $renderParams['shipping_country_options'] = $params['shipping_country_options'];
    if (isset($params['shipping_city_options'])) $renderParams['shipping_city_options'] = $params['shipping_city_options'];
    if (isset($params['shipping_city_label'])) $renderParams['shipping_city_label'] = $params['shipping_city_label'];
    $html = $renderer->renderCartEditForm($params['cart'], $renderParams);
    return $html;
}
