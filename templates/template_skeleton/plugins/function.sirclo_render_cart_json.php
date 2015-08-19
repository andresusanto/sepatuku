<?php
 /**    Plugin File */
 /**
  * Function to generate json representation of the cart information
  *
  * This function generate json representation of the cart information
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string json code of the cart information
  */
function smarty_function_sirclo_render_cart_json($params, $template)
{
    $jsonData = array();
    $renderer = new Renderer_Cart();
    $renderer->cartTableMode = 'mini';
    if ($message = $template->smarty->getTemplateVars('message')) {
        $jsonData['message'] = $message;
        $jsonData['message_type'] = $template->smarty->getTemplateVars('message_type');
    }
    $cart = $params['cart'];
    $jsonData['cart'] = Helper_Structure::filterArrayByKeys($cart, array(
        'currency_symbol',
        'total_items',
        'grand_total',
    ));
    $jsonData['cart_html'] = $renderer->renderCartTable($cart);
    return json_encode($jsonData);
}
