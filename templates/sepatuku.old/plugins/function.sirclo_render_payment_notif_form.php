<?php
 /**    Plugin File */
 /**
  * Function to generate payment notification form
  *
  * This function generate payment notification form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the payment notification form <form>...</form>
  */
function smarty_function_sirclo_render_payment_notif_form($params, $template) {
    $_html = Helper_Renderer::sircloRenderPaymentNotifForm($params);
    return $_html;
}
