<?php
function smarty_function_cc_render_ga_ecommerce_script($params, $template)
{
    $html = '';
    if (isset($params['order'])) {
        $html = Helper_Analytics::renderEcommerceScript($params['order']);
    }
    return $html;
}
