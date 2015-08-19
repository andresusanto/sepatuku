<?php
 /**    Plugin File */
 /**
  * Function to generate cart table
  *
  * This function generate cart table based on the option given in $params. Those options are 
  * <ul>
  *  <li>with_image: To show the image of the cart item</li>
  *  <li>with_edit_item: Able to edit the item</li>
  *  <li>with_break_options: Decide whether there is break option or not</li>
  * </ul>
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the edit cart form <form>...</form>
  */
function smarty_function_sirclo_render_cart_table($params, $template)
{
    $renderer = Renderer_Cart::getInstanceFromSmartyParams($params, $template);
    $renderParams = array();
    if (isset($params['with_image'])) $renderParams['with_image'] = $params['with_image'];
    if (isset($params['with_edit_item'])) $renderParams['with_edit_item'] = $params['with_edit_item'];
    if (isset($params['with_break_options'])) $renderParams['with_break_options'] = $params['with_break_options'];
    $tableHtml = $renderer->renderCartTable($params['cart'], $renderParams);
    return $tableHtml;
}
